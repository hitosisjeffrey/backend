<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Blog;
class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'demo@example.com')->first();

        if ($user) {
            Blog::factory()->count(5)->create([
                'created_by' => $user->id,
                'status' => 'PUBLISHED',
            ]);
            Blog::factory()->count(5)->create([
                'created_by' => $user->id,
                'status' => 'DRAFT',
            ]);
        }
    }
}
