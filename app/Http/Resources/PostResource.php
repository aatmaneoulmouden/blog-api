<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'shortDescription' => $this->short_description,
            'content' => $this->content,
            'featuredImageUrl' => $this->featured_image,
            'categories' => $this->categories->map(function ($category) {
                return [
                    'id' => (string)$category->id,
                    'name' => $category->name,
                ];
            }),
            'author' => [
                'id' => (string)$this->user->id,
            ]
        ];
    }
}
