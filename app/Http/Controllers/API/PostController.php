<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostListByUserRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class PostController extends Controller
{
    /**
     * Create new post
     *
     * @param PostCreateRequest $request
     *
     * @return JsonResponse
     */
    public function create(PostCreateRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();

            // Create new post
            $post = new Post($validated);
            $post->category_id = $validated['category'] ?? null;
            $post->author = auth()->id();
            $post->save();

            // Create associated tags and include "new" tag
            $validated['tags'][] = TAG::NEW_TAG;
            $post->tags()->attach($validated['tags']);

            DB::commit();

            $response = ['post' => $post];

            return response()->json($response, 201);
        } catch (Throwable $e) {
            DB::rollBack();

            // Log the error
            logger()->error($e->getMessage());

            return response()->json(['message' => 'Post was not created. An error occurred.'], 500);
        }
    }

    /**
     * Update a post
     *
     * @param Request $request
     * @param Post $post
     *
     * @return void
     */
    public function update(Request $request, Post $post)
    {
        try {

        } catch (Throwable $e) {
            logger()->error($e->getMessage());
        }
    }

    /**
     * Get list of all posts by user
     *
     * @param PostListByUserRequest $request
     * @param User $author
     *
     * @return void
     */
    public function getListByUser(PostListByUserRequest $request, User $author)
    {
        try {

        } catch (Throwable $e) {
            logger()->error($e->getMessage());
        }
    }

}
