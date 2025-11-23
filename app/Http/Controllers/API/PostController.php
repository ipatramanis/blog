<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostListByUserRequest;
use App\Http\Requests\PostListRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @param PostUpdateRequest $request
     * @param Post $post
     *
     * @return JsonResponse
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();

            $post->fill($validated);

            // Add tag "edited" if content has changed
            if ($post->isDirty('content')) {
                $validated['tags'][] = Tag::EDITED_TAG;
            }

            // Update tags if field is present
            if (isset($validated['tags'])) {
                $post->tags()->sync($validated['tags']);
            }

            $post->save();

            DB::commit();

            $response = ['post' => $post];

            return response()->json($response, 200);
        } catch (Throwable $e) {
            DB::rollBack();

            logger()->error($e->getMessage());

            return response()->json(['message' => 'Post was not updated. An error occurred.'], 500);
        }
    }

    /**
     * Delete a post
     *
     * @param Post $post
     *
     * @return JsonResponse
     */
    public function delete(Post $post)
    {
        try {
            DB::beginTransaction();

            $post->delete();

            DB::commit();

            $response = ['message' => 'Post was deleted successfully.'];

            return response()->json($response, 204);
        } catch (Throwable $e) {
            DB::rollBack();

            logger()->error($e->getMessage());

            return response()->json(['message' => 'Post was not deleted. An error occurred.'], 500);
        }
    }

    /**
     * Get single post by id and slug
     *
     * @param int $post_id
     * @param string $slug
     *
     * @return JsonResponse
     */
    public function get(int $post_id, string $slug)
    {
        try {
            DB::beginTransaction();

            $post = Post::whereLike('slug', sprintf('%%%s%%', $slug))
                ->where('id', $post_id)
                ->with(['author', 'tags'])
                ->get();

            DB::commit();

            // Throw not found exception
            if ($post->isEmpty()) {
                throw new NotFoundHttpException('Post not found.', code: 404);
            }

            return response()->json($post, 200);
        } catch (Throwable $e) {
            DB::rollBack();

            logger()->error($e->getMessage());

            $codeStatus = $e->getCode() == 404 ? $e->getCode() : 500;
            $message = $codeStatus == 404 ? $e->getMessage() : 'Failed to retrieve a post. An error occurred.';

            return response()->json(['message' => $message], $codeStatus);
        }
    }

    /**
     * Get a list of all posts, filter results by author, category and/or tags
     *
     * @param PostListRequest $request
     *
     * @return JsonResponse
     */
    public function getList(PostListRequest $request)
    {
        try {
            DB::beginTransaction();

            $query = Post::query();

            // Apply filters conditionally
            if (!empty($request->validated(['filter_by']))) {
                // Retrieve filter values
                $filterBy = $request->validated(['filter_by']);

                // Filter by author
                if (isset($filterBy['author_id'])) {
                    $query->where('author', '=', $filterBy['author_id']);
                }

                // Filter by category
                if (isset($filterBy['category_id'])) {
                    $query->where('category_id', '=', $filterBy['category_id']);
                }

                // Filter by tags
                if (isset($filterBy['tags'])) {
                    $tags[] = implode(',', $filterBy['tags']);
                    $query->whereHas('tags', function ($query) use ($tags) {
                        $query->whereIn('tags.id', $tags);
                    });
                }
            }

            // Get posts with relationships
            $posts = $query->with(['author', 'category', 'tags'])->get();

            DB::commit();

            return response()->json($posts, 200);
        } catch (Throwable $e) {
            DB::rollBack();

            logger()->error($e->getMessage());

            return response()->json(['message' => 'An error occurred. Failed to retrieve posts.'], 500);
        }
    }

    /**
     * Get list of all posts by user
     *
     * @param int $user_id
     *
     * @return JsonResponse
     */
    public function getListByUser(int $user_id)
    {
        try {
            DB::beginTransaction();

            $posts = Post::whereHas('author', function ($query) use ($user_id) {
                $query->where('id', $user_id);
            })->get();

            DB::commit();

            // Throw not found exception
            if ($posts->isEmpty()) {
                throw new NotFoundHttpException('No posts found for this user.', code: 404);
            }

            return response()->json($posts, 200);
        } catch (Throwable $e) {
            logger()->error($e->getMessage());

            $codeStatus = $e->getCode() == 404 ? $e->getCode() : 500;
            $message = $codeStatus == 404 ? $e->getMessage() : 'Failed to retrieve a list of posts. An error occurred.';

            return response()->json(['message' => $message], $codeStatus);
        }
    }
}
