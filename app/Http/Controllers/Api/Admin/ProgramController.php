<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ProgramController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $query = Program::query();
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        $programs = $query->orderBy('order')->paginate(10);
        return $this->success(ProgramResource::collection($programs)->response()->getData(true), 'Data retrieved successfully');
    }

    public function store(StoreProgramRequest $request): JsonResponse
    {
        $program = Program::create($request->validated());
        return $this->success(new ProgramResource($program), 'Program created successfully', 201);
    }

    public function show(Program $program): JsonResponse
    {
        return $this->success(new ProgramResource($program), 'Program retrieved successfully');
    }

    public function update(UpdateProgramRequest $request, Program $program): JsonResponse
    {
        $program->update($request->validated());
        return $this->success(new ProgramResource($program), 'Program updated successfully');
    }

    public function destroy(Program $program): JsonResponse
    {
        $program->delete();
        return $this->success(null, 'Program deleted successfully');
    }
}
