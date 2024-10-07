<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Article;

class FetchNewsArticles extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch articles from external APIs and store in the database';

    public function handle()
    {
        $sources = [
            'https://newsapi.org/v2/everything?q=technology&apiKey=55d5ce617ec24dd89f0cb5a0a9735d8a',
            'https://content.guardianapis.com/search?api-key=d813f7bb-ecad-4cc5-9e10-c409f3a54f0d',
            'https://api.nytimes.com/svc/archive/v1/2024/1.json?api-key=Ea2tUqZwSfRPaJMPNoEdWAcCv2Oi3qwa'
        ];

        foreach ($sources as $source) {
            $response = Http::get($source);

            if ($response->successful()) {
                $articles = $response->json()['articles'] ?? [];

                foreach ($articles as $article) {
                    Article::updateOrCreate(
                        ['title' => $article['title']], // Unique identifier to avoid duplicates
                        [
                            'title' => $article['title'],
                            'content' => $article['content'] ?? '',
                            'source' => $article['source']['name'],
                            'category' => 'Technology', // You can add dynamic logic to map categories
                            'published_at' => $article['publishedAt'],
                            'url' => $article['url']
                        ]
                    );
                }
            } else {
                $this->error('Failed to fetch data from ' . $source);
            }
        }

        $this->info('News articles fetched and stored successfully.');
    }
}
