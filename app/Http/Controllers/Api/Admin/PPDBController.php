<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePPDBRequest;
use App\Http\Requests\UpdatePPDBRequest;
use App\Http\Resources\PPDBResource;
use App\Models\Ppdb;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class PPDBController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $ppdbs = Ppdb::latest()->paginate(10);
        return $this->success(PPDBResource::collection($ppdbs)->response()->getData(true), 'Data retrieved successfully');
    }

    public function store(StorePPDBRequest $request): JsonResponse
    {
        $ppdb = Ppdb::create($request->validated());
        return $this->success(new PPDBResource($ppdb), 'PPDB created successfully', 201);
    }

    public function show(Ppdb $ppdb): JsonResponse
    {
        return $this->success(new PPDBResource($ppdb), 'PPDB retrieved successfully');
    }

    public function update(UpdatePPDBRequest $request, Ppdb $ppdb): JsonResponse
    {
        $ppdb->update($request->validated());
        return $this->success(new PPDBResource($ppdb), 'PPDB updated successfully');
    }

    public function destroy(Ppdb $ppdb): JsonResponse
    {
        $ppdb->delete();
        return $this->success(null, 'PPDB deleted successfully');
    }
}
