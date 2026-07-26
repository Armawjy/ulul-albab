<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    use ApiResponse;

    /**
     * Get all website settings as key-value pairs.
     */
    public function index(): JsonResponse
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        
        // Append full url for image type settings if they exist
        $imageKeys = ['logo_website', 'banner_image', 'hero_image', 'galeri_banner', 'berita_banner', 'guru_banner', 'program_banner', 'prestasi_banner'];
        foreach ($imageKeys as $key) {
            if (!empty($settings[$key]) && !str_starts_with($settings[$key], 'http')) {
                $settings[$key . '_url'] = asset('storage/' . $settings[$key]);
            } elseif (!empty($settings[$key])) {
                $settings[$key . '_url'] = $settings[$key];
            }
        }

        return $this->success($settings, 'Pengaturan website berhasil diambil');
    }

    /**
     * Update website settings (accepts text and files).
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->except(['_token', '_method']);
        $imageKeys = ['logo_website', 'banner_image', 'hero_image', 'galeri_banner', 'berita_banner', 'guru_banner', 'program_banner', 'prestasi_banner'];

        foreach ($data as $key => $value) {
            // Check if it's an uploaded file
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $path = $file->store('settings', 'public');
                
                // Delete old file if exists
                $oldSetting = Setting::where('key', $key)->first();
                if ($oldSetting && $oldSetting->value && !str_starts_with($oldSetting->value, 'http') && Storage::disk('public')->exists($oldSetting->value)) {
                    Storage::disk('public')->delete($oldSetting->value);
                }

                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $path, 'type' => 'image']
                );
            } elseif (!is_null($value) && !in_array($key, $imageKeys, true)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => is_array($value) ? json_encode($value) : (string) $value, 'type' => 'string']
                );
            }
        }

        return $this->index();
    }
}
