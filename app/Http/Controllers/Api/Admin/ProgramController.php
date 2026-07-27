<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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
        $data = $request->validated();
        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon')->store('programs', 'public');
        }
        $program = Program::create($data);
        return $this->success(new ProgramResource($program), 'Program created successfully', 201);
    }

    public function show(Program $program): JsonResponse
    {
        return $this->success(new ProgramResource($program), 'Program retrieved successfully');
    }

    public function update(UpdateProgramRequest $request, Program $program): JsonResponse
    {
        $data = $request->validated();
        if ($request->hasFile('icon')) {
            if ($program->icon && !str_starts_with($program->icon, 'http') && !in_array($program->icon, ['Microscope', 'Globe', 'Book', 'Languages', 'Coins', 'Monitor', 'Lightbulb', 'Users', 'Speech', 'Star'])) {
                Storage::disk('public')->delete($program->icon);
            }
            $data['icon'] = $request->file('icon')->store('programs', 'public');
        }
        $program->update($data);
        return $this->success(new ProgramResource($program), 'Program updated successfully');
    }

    public function destroy(Program $program): JsonResponse
    {
        if ($program->icon && !str_starts_with($program->icon, 'http') && !in_array($program->icon, ['Microscope', 'Globe', 'Book', 'Languages', 'Coins', 'Monitor', 'Lightbulb', 'Users', 'Speech', 'Star'])) {
            Storage::disk('public')->delete($program->icon);
        }
        $program->delete();
        return $this->success(null, 'Program deleted successfully');
    }
}
