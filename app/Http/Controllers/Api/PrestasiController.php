<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Http\Resources\PrestasiResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class PrestasiController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $prestasis = Prestasi::latest('year')->paginate(12);
        return $this->success(PrestasiResource::collection($prestasis)->response()->getData(true), 'Prestasis retrieved successfully.');
    }
}
