<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBeritaRequest;
use App\Http\Requests\UpdateBeritaRequest;
use App\Http\Resources\BeritaResource;
use App\Models\Berita;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $beritas = Berita::latest()->paginate(10);
        return $this->success(BeritaResource::collection($beritas)->response()->getData(true), 'Data retrieved successfully');
    }

    public function store(StoreBeritaRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['title']);
        $data['author_id'] = auth()->id() ?? 1;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('berita', 'public');
        }

        $berita = Berita::create($data);
        return $this->success(new BeritaResource($berita), 'Berita created successfully', 201);
    }

    public function show(Berita $berita): JsonResponse
    {
        return $this->success(new BeritaResource($berita), 'Berita retrieved successfully');
    }

    public function update(UpdateBeritaRequest $request, Berita $berita): JsonResponse
    {
        $data = $request->validated();
        
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('thumbnail')) {
            if ($berita->thumbnail) Storage::disk('public')->delete($berita->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('berita', 'public');
        }

        $berita->update($data);
        return $this->success(new BeritaResource($berita), 'Berita updated successfully');
    }

    public function destroy(Berita $berita): JsonResponse
    {
        if ($berita->thumbnail) Storage::disk('public')->delete($berita->thumbnail);
        $berita->delete();
        return $this->success(null, 'Berita deleted successfully');
    }
}
