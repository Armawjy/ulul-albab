<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProgramResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon ? (str_starts_with($this->icon, 'http') ? $this->icon : (str_contains($this->icon, '/') || str_contains($this->icon, '.') ? asset('storage/' . $this->icon) : $this->icon)) : null,
            'description' => $this->description,
            'order' => $this->order,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
