<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Feed;

class CommentFactory extends Factory
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
            'user_id' => User::factory(),           // ID user yang membuat komentar
            'feed_id' => Feed::factory(),           // ID feed yang dikomentari
            'comment' => $this->faker->sentence(),     // Isi komentar (kalimat acak)
            'created_at' => $createdAt,             // Waktu pembuatan komentar
            'updated_at' => $createdAt,             // Sama dengan created_at (default tidak berubah)
        ];
    }
}
