<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 50; $i++) {
            Article::create([
                'title' => 'Sample Article ' . ($i + 1),
                'content' => 'This is the content of sample article ' . ($i + 1),
                'category' => 'Category ' . (($i % 5) + 1),
                'source' => 'Source ' . (($i % 3) + 1),
                'published_at' => now()->subDays(rand(1, 30))
            ]);
        }
    }
}
