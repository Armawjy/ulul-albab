<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sejarah;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SejarahController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $sejarah = Sejarah::orderBy('year', 'asc')->get();
        return $this->success($sejarah, 'Sejarah retrieved successfully.');
    }
}
