<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Article;
use App\Models\Source;
use Carbon\Carbon;

class NewsAggregatorService
{
    public function fetchNews()
    {
        $this->fetchFromNewsAPI();
        $this->fetchFromGuardian();
        $this->fetchFromNYT();
    }

    private function fetchFromNewsAPI()
    {
        $response = Http::get(config('services.newsapi.base_url') . '/top-headlines', [
            'apiKey' => config('services.newsapi.api_key'),
            'country' => 'us',
            'category' => 'general',
        ]);

        if ($response->successful()) {
            $articles = $response->json()['articles'] ?? [];
            $this->storeArticles($articles, 'newsapi');
        }
    }

    private function fetchFromGuardian()
    {
        $response = Http::get(config('services.guardian.base_url') . '/search', [
            'api-key' => config('services.guardian.api_key'),
            'show-fields' => 'headline,bodyText,thumbnail,byline',
        ]);

        if ($response->successful()) {
            $articles = $response->json()['response']['results'] ?? [];
            $this->storeArticles($articles, 'the-guardian');
        }
    }

    private function fetchFromNYT()
    {
        $response = Http::get(config('services.nyt.base_url') . '/home.json', [
            'api-key' => config('services.nyt.api_key'),
        ]);

        if ($response->successful()) {
            $articles = $response->json()['results'] ?? [];
            $this->storeArticles($articles, 'nyt');
        }
    }

    private function storeArticles(array $articles, string $sourceApiId)
    {
        $source = Source::where('api_id', $sourceApiId)->first();

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['url' => $article['url'] ?? ($article['webUrl'] ?? '')],
                [
                    'title' => $article['title'] ?? ($article['webTitle'] ?? ''),
                    'content' => $article['description'] ?? ($article['fields']['bodyText'] ?? ''),
                    'image_url' => $article['urlToImage'] ?? ($article['fields']['thumbnail'] ?? null),
                    'published_at' => isset($article['publishedAt']) ? Carbon::parse($article['publishedAt']) : now(),
                    'source_id' => $source->id ?? null,
                    'category' => $article['section'] ?? 'general',
                    'author' => $article['author'] ?? ($article['fields']['byline'] ?? 'Unknown'),
                ]
            );
        }
    }
}
