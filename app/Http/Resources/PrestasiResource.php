<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PrestasiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'level' => $this->level,
            'rank' => $this->rank,
            'winner_name' => $this->winner_name,
            'year' => $this->year,
            'image' => $this->image ? (str_starts_with($this->image, 'http') ? $this->image : asset('storage/' . $this->image)) : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
