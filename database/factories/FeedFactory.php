<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FeedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Tentukan jenis file secara acak (image atau video)
        $isImage = $this->faker->boolean(70); // 70% kemungkinan file adalah image
        $filePath = $isImage
            ? $this->generateDummyImage()
            : $this->generateDummyVideo();
        
        $createdAt = $this->faker->dateTimeBetween('-1 years', 'now'); // Waktu pembuatan

        return [
            'user_id' => \App\Models\User::factory(), // Hubungkan ke user secara otomatis
            'filename' => $filePath, // Path file yang dihasilkan
            'file_type' => $isImage ? 'image' : 'video', // Tipe file
            'caption' => $this->faker->sentence(10), // Caption random
            'created_at' => $createdAt,             // Waktu pembuatan
            'updated_at' => $createdAt,             // Sama dengan created_at (belum ada perubahan)
        ];
    }

    /**
     * Generate a dummy image file.
     */
    private function generateDummyImage()
    {
        // Simpan gambar di storage/public/feeds
        $imagePath = 'public/feeds/' . Str::uuid() . '.jpg';
        $this->faker->image(Storage::path('public/feeds'), 640, 480, null, false, true, null, false);
        return $imagePath;
    }

    /**
     * Generate a dummy video file.
     */
    private function generateDummyVideo()
    {
        // Simpan video dummy di storage/public/feeds
        $videoFormats = ['mp4', 'mov'];
        $format = $this->faker->randomElement($videoFormats);

        $videoPath = 'public/feeds/' . Str::uuid() . '.' . $format;
        Storage::put($videoPath, Str::random(1000)); // Simpan file dummy dengan konten random
        return $videoPath;
    }
}
