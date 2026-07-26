<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Http\Resources\GaleriResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = Galeri::query();
        
        if ($request->has('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        $galeris = $query->latest()->paginate(16);
        return $this->success(GaleriResource::collection($galeris)->response()->getData(true), 'Galeris retrieved successfully.');
    }
}
