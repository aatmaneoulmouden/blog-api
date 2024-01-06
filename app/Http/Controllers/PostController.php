<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
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
    public function store(Request $request)
    {
        // Get authenticated user
        $user = $request->user();

        // Check if user exist
        if (!$user) {
            return $this->error('User not found.', 422);
        }

        // Check if user already have a post with the same title
        $postTitle = $request->input('title');
        $postTitle = $user->posts()->where('title', $postTitle)->first();

        if ($postTitle) {
            return $this->error('You already have a post with the same title.', 422);
        }

        // Create post
        $post = Post::create([
            'user_id' => $user->id,
            'user_id' => $user->id,
            'name' => $postTitle,
            'slug' => Str::slug($postTitle),
        ]);

        // Get tag data
        $tagData = new TagResource($tag);

        // Return http response
        return $this->success('category', $tagData, 201);
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
