<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profil;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ProfilController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $profil = Profil::first();
        return $this->success($profil, 'Profil retrieved successfully.');
    }
}
