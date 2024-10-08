<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class ArticleFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_fetch_articles_with_pagination()
    {
        // Create multiple articles using the factory
        Article::factory()->count(15)->create();

        $response = $this->getJson('/api/articles?page=1&per_page=10');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data', 'links'
        ]);
    }

    public function test_it_can_search_articles_by_keyword()
    {
        // Create articles
        $article1 = Article::factory()->create(['title' => 'Breaking News about Technology']);
        $article2 = Article::factory()->create(['title' => 'Another article unrelated to the keyword']);

        // Log created articles to verify
        Log::info('Created Articles:', Article::all()->toArray());

        // Perform the search for the keyword
        $response = $this->getJson('/api/articles/search?keyword=Technology');

        // Log the response data
        Log::info('Search Response:', $response->json());

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that there's at least one item in the data array
        $this->assertNotEmpty($response->json('data'), 'The response data is empty.');

        // Assert that the response contains the correct article
        $response->assertJsonFragment(['title' => 'Breaking News about Technology']);
    }
}
