<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Http\Resources\ProgramResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ProgramController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $programs = Program::orderBy('order')->get();
        return $this->success(ProgramResource::collection($programs)->response()->getData(true), 'Programs retrieved successfully.');
    }
}
