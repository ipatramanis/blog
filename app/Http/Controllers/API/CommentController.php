<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentCreateRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class CommentController extends Controller
{
    /**
     * Create single comment
     *
     * @param CommentCreateRequest $request
     * @return JsonResponse
     */
    public function create(CommentCreateRequest $request)
    {
        try {
            $validatedFields = $request->validated();

            DB::beginTransaction();

            // Create new comment
            $comment = (new Comment($validatedFields));
            $comment->user_id = auth()->id();
            $comment->save();

            DB::commit();

            return response()->json($comment, 200);
        } catch (Throwable $e) {
            DB::rollBack();

            logger($e->getMessage());

            return response()->json(['message' => 'Failed to create new comment. An error occurred.'], 500);
        }
    }

    /**
     * Get a list of user comments
     *
     * @param int $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListByUser(int $user_id)
    {
        try {
            DB::beginTransaction();

            $userComments = Comment::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

            DB::commit();

            // Throw not found exception
            if ($userComments->isEmpty()) {
                throw new NotFoundHttpException('Comments not found.', code: 404);
            }

            return response()->json($userComments, 200);
        } catch (Throwable $e) {
            DB::rollBack();

            logger()->error($e->getMessage());

            // Set http status code and according message
            $statusCode = $e->getCode() == 404 ? $e->getCode() : 500;
            $message = $e->getCode() == 404 ? $e->getMessage() : 'An error occurred. Failed to retrieve user comments.';

            return response()->json(['message' => $message], $statusCode);
        }
    }
}
