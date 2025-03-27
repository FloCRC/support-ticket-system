<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Create a new category.
     */
    public function createCategory(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $category = new Category();
        $category->name = $request->name;

        if ($category->save()) {
            return response()->json([
                'message' => "Category successfully created.",
                'success' => true
            ], 201);
        }

        return response()->json([
            'message' => "An error occurred. Category not created.",
            'success' => false
        ], 500);
    }

    /**
     * Get all categories.
     */
    public function getAllCategories(): JsonResponse
    {
        $categories = $this->category->all();

        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'No categories found.',
                'success' => false,
            ], 404);
        }

        return response()->json([
            'message' => "categories successfully retrieved.",
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Display the specified category.
     */
    public function getCategory(int $categoryId): JsonResponse
    {
        $category = $this->category->find($categoryId);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found.',
                'success' => false,
            ], 404);
        }

        return response()->json([
            'message' => "Category successfully retrieved.",
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * Edit the specified category.
     */
    public function editCategory(int $categoryId, Request $request): JsonResponse
    {
        $category = $this->category->find($categoryId);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found.',
                'success' => false,
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $category->name = $request->name;

        if ($category->save()) {
            return response()->json([
                'message' => "Category successfully edited.",
                'success' => true
            ]);
        }

        return response()->json([
            'message' => "An error occurred. Category not updated.",
            'success' => false,
        ], 500);
    }

    /**
     * Remove the specified category.
     */
    public function deleteCategory(int $categoryId): JsonResponse
    {
        $category = $this->category->find($categoryId);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found.',
                'success' => false,
            ], 404);
        }

        if ($category->delete()) {
            return response()->json([
                'message' => "Category successfully deleted.",
                'success' => true
            ]);
        }

        return response()->json([
            'message' => "An error occurred. Category not deleted.",
            'success' => false,
        ], 500);
    }
}
