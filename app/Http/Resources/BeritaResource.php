<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BeritaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'category' => $this->category,
            'thumbnail' => $this->thumbnail ? (str_starts_with($this->thumbnail, 'http') ? $this->thumbnail : asset('storage/' . $this->thumbnail)) : null,
            'author_id' => $this->author_id,
            'author_name' => $this->whenLoaded('author', fn() => $this->author->name),
            'status' => $this->status,
            'published_at' => $this->published_at ? $this->published_at->format('Y-m-d H:i:s') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
