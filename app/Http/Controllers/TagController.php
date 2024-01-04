<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
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

        // Get authenticated user's tags
        $tags = $user->tags()->get();
        $tagsData = new TagCollection($tags);

        return $this->success('tags', $tagsData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        // Get authenticated user
        $user = $request->user();

        // Check if user exist
        if (!$user) {
            return $this->error('User not found.', 422);
        }

        $tagName = $request->input('name');

        // Check if user already have cat. with the same name
        $tagExist = $user->tags()->where('name', $tagName)->first();

        if ($tagExist) {
            return $this->error('You already have a tag with the same name.', 422);
        }

        // Create tag
        $tag = Tag::create([
            'user_id' => $user->id,
            'name' => $tagName,
            'slug' => Str::slug($tagName),
        ]);

        // Get tag data
        $tagData = new TagResource($tag);

        // Return http response
        return $this->success('category', $tagData, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        // Get authenticated user
        $user = $request->user();

        // Check if user exist
        if (!$user) {
            return $this->error('User not found.', 404);
        }

        // Check if tag exist
        if (!$tag) {
            return $this->error('Tag not found.', 404);
        }

        // Check if tag belongs to authenticated user
        if ($tag->user_id != $user->id) {
            return $this->error('Unauthorized.', 403);
        }

        // Get new tag name
        $tagName = $request->input('name');

        // Check if user already have cat. with the same name
        $tagExist = $user->categories()->where('name', $tagName)->first();

        if ($tagExist) {
            return $this->error('You already have a tag with the same name.', 422);
        }

        // Update tag
        $tag->update([
            'name' => $tagName,
            'slug' => Str::slug($tagName),
        ]);

        $tagData = new TagResource($tag);

        // Return http response
        return $this->success('category', $tagData, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag, Request $request)
    {
        // Get authenticated user
        $user = $request->user();

        // Check if user exist
        if (!$user) {
            return $this->error('User not found.', 404);
        }

        // Check if tag exist
        if (!$tag) {
            return $this->error('Tag not found.', 404);
        }

        // Check if tag belongs to authenticated user
        if ($tag->user_id != $user->id) {
            return $this->error('Unauthorized.', 403);
        }

        // Delete tag
        $tag->delete();

        // Return http response
        return $this->success(null, null, 204);
    }
}
