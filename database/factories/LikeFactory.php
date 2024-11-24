<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Feed;

class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $createdAt = $this->faker->dateTimeBetween('-1 years', 'now'); // Waktu pembuatan

        return [
            'user_id' => User::factory(),           // ID user yang memberi like
            'feed_id' => Feed::factory(),           // ID feed yang di-like
            'created_at' => $createdAt,             // Waktu pembuatan
            'updated_at' => $createdAt,             // Sama dengan created_at (belum ada perubahan)
        ];
    }
}
