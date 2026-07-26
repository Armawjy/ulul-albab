<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Guru;
use App\Models\Prestasi;
use App\Models\Program;
use App\Models\Slider;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\BeritaResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $sliders = Slider::where('is_active', true)->orderBy('order')->get()->map(function ($slider) {
            $slider->image = $slider->image ? (str_starts_with($slider->image, 'http') ? $slider->image : asset('storage/' . $slider->image)) : null;
            return $slider;
        });

        $programs = Program::orderBy('order')->take(4)->get();
        $beritas = Berita::with('author')->where('status', 'published')->latest('published_at')->take(3)->get();
        $prestasis = Prestasi::latest('year')->take(4)->get();

        $data = [
            'sliders' => $sliders,
            'programs' => ProgramResource::collection($programs)->response()->getData(true)['data'] ?? [],
            'beritas' => BeritaResource::collection($beritas)->response()->getData(true)['data'] ?? [],
            'prestasis' => \App\Http\Resources\PrestasiResource::collection($prestasis)->response()->getData(true)['data'] ?? [],
            'stats' => [
                'guru' => Guru::count(),
                'prestasi' => Prestasi::count(),
                'santri' => 1250, // Dummy, if there's no model for this
                'alumni' => 2300, // Dummy
            ]
        ];

        return $this->success($data, 'Home data retrieved successfully.');
    }
}
