<?php

namespace App\Http\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Validate request input against user's credentials
     *
     * @param array $credentials
     * @param User $user
     *
     * @return true
     * @throws Exception
     */
    public function validateUserCredentials(array $credentials, User $user)
    {
        // Validate check password
        if (!Hash::check($credentials['password'], $user->password)) {
            throw new Exception('Unauthorized: Invalid credentials.', 401);
        }

        // Validate check email
        if ($credentials['email'] !== $user->email) {
            throw new Exception('Unauthorized: Invalid credentials.', 401);
        }

        return true;
    }

}
