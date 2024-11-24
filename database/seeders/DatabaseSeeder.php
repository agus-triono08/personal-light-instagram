<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Feed;
use App\Models\Comment;
use App\Models\Like;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Buat users
        User::factory(10)->create();

        // Buat feeds
        Feed::factory(50)->create();

        // Buat likes dan comments
        Like::factory(200)->create();
        Comment::factory(100)->create();
    }
}
