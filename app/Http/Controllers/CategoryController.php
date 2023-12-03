<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get authenticated user
        $user = $request->user();

        // Check if user exist
        if (!$user) {
            return $this->error('User not found.', 422);
        }

        // Get authenticated user's categories
        $categories = $user->categories()->get();
        $categoriesData = new CategoryCollection($categories);

        return $this->success('categories', $categoriesData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        // Get authenticated user
        $user = $request->user();

        // Check if user exist
        if (!$user) {
            return $this->error('User not found.', 422);
        }

        $categoryName = $request->input('name');

        // Check if user already have cat. with the same name
        $categoryExist = $user->categories()->where('name', $categoryName)->first();

        if ($categoryExist) {
            return $this->error('You already have a category with the same name.', 422);
        }

        // Create category
        $category = Category::create([
            'user_id' => $user->id,
            'name' => $categoryName,
            'slug' => Str::slug($categoryName),
        ]);

        // Get category name
        $categoryData = new CategoryResource($category);

        // Return http response
        return $this->success('category', $categoryData, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // Get authenticated user
        $user = $request->user();

        // Check if user exist
        if (!$user) {
            return $this->error('User not found.', 404);
        }

        // Check if category exist
        if (!$category) {
            return $this->error('Category not found.', 404);
        }

        // Check if category belongs to authenticated user
        if ($category->user_id != $user->id) {
            return $this->error('Unauthorized.', 403);
        }

        // Get new category name
        $categoryName = $request->input('name');

        // Check if user already have cat. with the same name
        $categoryExist = $user->categories()->where('name', $categoryName)->first();

        if ($categoryExist) {
            return $this->error('You already have a category with the same name.', 422);
        }

        // Update category
        $category->update([
            'name' => $categoryName,
            'slug' => Str::slug($categoryName),
        ]);

        $categoryData = new CategoryResource($category);

        // Return http response
        return $this->success('category', $categoryData, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, Request $request)
    {
        // Get authenticated user
        $user = $request->user();

        // Check if user exist
        if (!$user) {
            return $this->error('User not found.', 404);
        }

        // Check if category exist
        if (!$category) {
            return $this->error('Category not found.', 404);
        }

        // Check if category belongs to authenticated user
        if ($category->user_id != $user->id) {
            return $this->error('Unauthorized.', 403);
        }

        // Delete category
        $category->delete();

        // Return http response
        return $this->success(null, null, 204);
    }
}
