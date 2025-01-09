<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)
        // ->state([
        //     'email_verified_at' => '2030-02-13 23:31:30',
        // ])
        // ->create();
        $user = User::factory()
            ->has(Post::factory()->count(3))
            ->create();
    }
}
