<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Http\Resources\GuruResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class GuruController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $gurus = Guru::paginate(12);
        return $this->success(GuruResource::collection($gurus)->response()->getData(true), 'Gurus retrieved successfully.');
    }
}
