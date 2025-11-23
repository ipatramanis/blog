<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class CategoryController extends Controller
{
    /**
     * Get a list of all categories
     *
     * @return JsonResponse
     */
    public function getList()
    {
        try {
            DB::beginTransaction();

            $categoryList = Category::all();

            DB::commit();

            return response()->json($categoryList, 200);
        } catch (Throwable $e) {
            DB::rollBack();

            logger()->error($e->getMessage());

            return response()->json(['message' => 'Failed to get categories list. An error occurred.'], 500);
        }
    }
}
