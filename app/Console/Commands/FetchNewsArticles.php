<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Article;
use Carbon\Carbon;

class FetchNewsArticles extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch news articles from different sources';

    public function handle()
    {
        // Fetch articles from The Guardian
        $this->fetchFromGuardian();

        // Fetch articles from New York Times
        $this->fetchFromNewYorkTimes();

        // Fetch articles from NewsAPI
        $this->fetchFromNewsAPI();

        $this->info('News articles fetched successfully.');
    }

 

private function fetchFromGuardian()
{
    $response = Http::get(env('GUARDIAN_API_URL'));

    if ($response->successful()) {
        $articles = $response->json()['response']['results'] ?? [];

        foreach ($articles as $article) {
            $content = $article['fields']['bodyText'] ?? 'Content not available'; // Provide default content if missing
            Article::updateOrCreate(
                ['title' => $article['webTitle']],
                [
                    'title' => $article['webTitle'],
                    'content' => $content,
                    'source' => 'The Guardian',
                    'category' => $article['sectionName'],
                    'published_at' => Carbon::parse($article['webPublicationDate'])->toDateTimeString(),
                ]
            );
        }
    }
}


private function fetchFromNewYorkTimes()
{
    $response = Http::get(env('NYT_API_URL'));

    if ($response->successful()) {
        $articles = $response->json()['response']['docs'] ?? [];

        foreach ($articles as $article) {
            $author = $article['byline']['original'] ?? null;

            Article::updateOrCreate(
                ['title' => $article['headline']['main']],
                [
                    'title' => $article['headline']['main'],
                    'content' => $article['abstract'],
                    'author' => $author, // Extract author from byline
                    'source' => 'New York Times',
                    'category' => $article['section_name'] ?? 'General',
                    'published_at' => Carbon::parse($article['pub_date'])->toDateTimeString(),
                ]
            );
        }
    }
}

private function fetchFromNewsAPI()
{
    $response = Http::get(env('NEWS_API_URL'));

    if ($response->successful()) {
        $articles = $response->json()['articles'] ?? [];

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['title' => $article['title']],
                [
                    'title' => $article['title'],
                    'content' => $article['content'],
                    'author' => $article['author'] ?? 'Unknown', // Use author if available, otherwise set to 'Unknown'
                    'source' => $article['source']['name'],
                    'category' => 'General',
                    'published_at' => Carbon::parse($article['publishedAt'])->toDateTimeString(),
                ]
            );
        }
    }
}

}
