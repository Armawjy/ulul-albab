<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuruRequest;
use App\Http\Requests\UpdateGuruRequest;
use App\Http\Resources\GuruResource;
use App\Models\Guru;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $gurus = Guru::latest()->paginate(10);
        return $this->success(GuruResource::collection($gurus)->response()->getData(true), 'Data retrieved successfully');
    }

    public function store(StoreGuruRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('guru', 'public');
        }

        $guru = Guru::create($data);
        return $this->success(new GuruResource($guru), 'Guru created successfully', 201);
    }

    public function show(Guru $guru): JsonResponse
    {
        return $this->success(new GuruResource($guru), 'Guru retrieved successfully');
    }

    public function update(UpdateGuruRequest $request, Guru $guru): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($guru->image) Storage::disk('public')->delete($guru->image);
            $data['image'] = $request->file('image')->store('guru', 'public');
        }

        $guru->update($data);
        return $this->success(new GuruResource($guru), 'Guru updated successfully');
    }

    public function destroy(Guru $guru): JsonResponse
    {
        if ($guru->image) Storage::disk('public')->delete($guru->image);
        $guru->delete();
        return $this->success(null, 'Guru deleted successfully');
    }
}
