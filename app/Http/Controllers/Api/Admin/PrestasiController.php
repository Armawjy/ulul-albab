<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrestasiRequest;
use App\Http\Requests\UpdatePrestasiRequest;
use App\Http\Resources\PrestasiResource;
use App\Models\Prestasi;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PrestasiController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $prestasis = Prestasi::latest()->paginate(10);
        return $this->success(PrestasiResource::collection($prestasis)->response()->getData(true), 'Data retrieved successfully');
    }

    public function store(StorePrestasiRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('prestasi', 'public');
        }

        $prestasi = Prestasi::create($data);
        return $this->success(new PrestasiResource($prestasi), 'Prestasi created successfully', 201);
    }

    public function show(Prestasi $prestasi): JsonResponse
    {
        return $this->success(new PrestasiResource($prestasi), 'Prestasi retrieved successfully');
    }

    public function update(UpdatePrestasiRequest $request, Prestasi $prestasi): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($prestasi->image) Storage::disk('public')->delete($prestasi->image);
            $data['image'] = $request->file('image')->store('prestasi', 'public');
        }

        $prestasi->update($data);
        return $this->success(new PrestasiResource($prestasi), 'Prestasi updated successfully');
    }

    public function destroy(Prestasi $prestasi): JsonResponse
    {
        if ($prestasi->image) Storage::disk('public')->delete($prestasi->image);
        $prestasi->delete();
        return $this->success(null, 'Prestasi deleted successfully');
    }
}
