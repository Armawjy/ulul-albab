<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisiMisi;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class VisiMisiController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $data = [
            'visi' => VisiMisi::where('type', 'visi')->get(),
            'misi' => VisiMisi::where('type', 'misi')->get(),
            'tujuan' => VisiMisi::where('type', 'tujuan')->get(),
            'nilai' => VisiMisi::where('type', 'nilai')->get(),
        ];
        return $this->success($data, 'Visi Misi retrieved successfully.');
    }
}
