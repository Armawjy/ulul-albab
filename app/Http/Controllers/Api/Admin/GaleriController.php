<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGaleriRequest;
use App\Http\Requests\UpdateGaleriRequest;
use App\Http\Resources\GaleriResource;
use App\Models\Galeri;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $galeris = Galeri::latest()->paginate(10);
        return $this->success(GaleriResource::collection($galeris)->response()->getData(true), 'Data retrieved successfully');
    }

    public function store(StoreGaleriRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('galeri', 'public');
        }

        $galeri = Galeri::create($data);
        return $this->success(new GaleriResource($galeri), 'Galeri created successfully', 201);
    }

    public function show(Galeri $galeri): JsonResponse
    {
        return $this->success(new GaleriResource($galeri), 'Galeri retrieved successfully');
    }

    public function update(UpdateGaleriRequest $request, Galeri $galeri): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($galeri->image) Storage::disk('public')->delete($galeri->image);
            $data['image'] = $request->file('image')->store('galeri', 'public');
        }

        $galeri->update($data);
        return $this->success(new GaleriResource($galeri), 'Galeri updated successfully');
    }

    public function destroy(Galeri $galeri): JsonResponse
    {
        if ($galeri->image) Storage::disk('public')->delete($galeri->image);
        $galeri->delete();
        return $this->success(null, 'Galeri deleted successfully');
    }
}
