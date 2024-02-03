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

        // Get all tags
        $tags = Tag::all();
        $tagsData = new TagCollection($tags);

        return $this->success('tags', $tagsData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        //
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
        //    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag, Request $request)
    {
        //
    }
}
