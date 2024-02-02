<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
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

        // Duplicated title check
        $postTitle = $request->input('title');
        $postExist = $user->posts()->where('title', $postTitle)->first();

        if ($postExist) {
            return $this->error('The title is already taken.', 422);
        }

        // Validate categories
        // TODO: availableCategories => get only active categories
        $availableCategories = Category::pluck('id')->toArray();
        $selectedCategories = $request->input('categories', []);
        $unavailableCategories = [];
        foreach ($selectedCategories as $category) {
            if (!in_array($category, $availableCategories)) {
                array_push($unavailableCategories, $category);
            }
        }

        if ($unavailableCategories) {
            return $this->error('Some categories not found.', 404);
        }

        // Validate tags
        $insertedTags = $request->input('tags');
        $validatedTags = [];
        foreach ($insertedTags as $tagName) {
            $tag = Tag::firstOrCreate(
                ['name' => $tagName],
                ['slug' => Str::slug($tagName), 'user_id' => $user->id]
            );

            array_push($validatedTags, $tag->id);
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
        $post->categories()->sync($selectedCategories);

        // Assign tags to post
        $post->tags()->sync($validatedTags);

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
