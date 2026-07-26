<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Http\Resources\BeritaResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = Berita::with('author')->where('status', 'published');
        
        if ($request->has('category') && $request->category !== 'Semua Berita') {
            $query->where('category', $request->category);
        }

        $beritas = $query->latest('published_at')->paginate(9);
        return $this->success(BeritaResource::collection($beritas)->response()->getData(true), 'Beritas retrieved successfully.');
    }

    public function show($slug): JsonResponse
    {
        $berita = Berita::with('author')->where('slug', $slug)->firstOrFail();
        return $this->success(new BeritaResource($berita), 'Berita detail retrieved successfully.');
    }
}
