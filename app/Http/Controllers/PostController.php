<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // Get authenticated user
        $user = $request->user();

        // Check if user exist
        if (!$user) {
            return $this->error('User not found.', 422);
        }

        // Check if user already have a post with the same title
        $postTitle = $request->input('title');
        $postExist = $user->posts()->where('title', $postTitle)->first();

        if ($postExist) {
            return $this->error('You already have a post with the same title.', 422);
        }

        // Validate categories
        $userCategories = $user->categories()->distinct()->pluck('id')->toArray();
        $categoriesIds = $request->input('categories', []);
        
        if ($categoriesIds) {
            foreach ($categoriesIds as $categoryId) {
                if (!in_array($categoryId, $userCategories)) {
                    // Normaly will create it as new category,
                    // BUT Now return just an error message
                    $notExistingCategoryName = Category::whereId($categoryId)->pluck('name')->first();
                    return $this->error("You don't own '" . $notExistingCategoryName .  "' category, please create it first!", 400);
                }
            }
        }
        
        // Create post
        $post = Post::create([
            'user_id' => $user->id,
            'title' => $postTitle,
            'slug' => Str::slug($postTitle),
            'short_description' => $request->input('short_description'),
            'content' => $request->input('content'),
            'featured_image' => $request->input('featured_image'),
        ]);
          
        // Assign categories to post
        $post->categories()->attach($categoriesIds);

        // Get post data
        $postData = new PostResource($post);

        // Return http response
        return $this->success('post', $postData, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
