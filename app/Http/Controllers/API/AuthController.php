<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\AuthService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    /**
     * Register new user
     *
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            // Get validated input
            $validated = $request->safe()->all();

            DB::beginTransaction();

            // Create new user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Create authentication token for this user
            $token = $user->createToken('auth-token')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];

            DB::commit();

            return response()->json($response, 201);
        } catch (Throwable $ex) {
            DB::rollBack();

            return response()->json(['message' => 'Failed to register new user. An unexpected error occurred.'], 500);
        }
    }

    /**
     * Authenticate user and login
     *
     * @param LoginRequest $request
     * @param AuthService $authService
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request, AuthService $authService)
    {
        try {
            // Get validated input
            $credentials = $request->safe()->all();

            // Get user by email
            $user = User::where('email', $credentials['email'])->first();

            // Validate user credentials
            $authService->validateUserCredentials($credentials, $user);

            // Issue new token for each login
            $token = $user->createToken('auth-token')->plainTextToken;
            $response = ['token' => $token];

            return response()->json($response, 200);
        } catch (Throwable $ex) {
            return response()->json($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Logout user
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Revoke current token
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logged out'], 200);
        } catch (Throwable $ex) {
            return response()->json(['message' => 'Failed to log out user. An error occurred.'], 500);
        }
    }
}
