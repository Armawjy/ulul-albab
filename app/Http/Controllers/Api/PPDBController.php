<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ppdb;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class PpdbController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $ppdb = Ppdb::where('status', true)->latest()->first();
        return $this->success($ppdb, 'Active PPDB retrieved successfully.');
    }
}
