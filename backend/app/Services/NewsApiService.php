<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewsApiService extends NewsService {

    public function fetch( string $category = 'business', int $page ): mixed {

        $date = Carbon::yesterday()->format('Y-m-d');

        $response = Http::acceptJson()
                        ->get(
                            "https://newsapi.org/v2/top-headlines",
                            [
                                'category' => $category,
                                'apiKey' => config('app.news_api_key'),
                                'sortBy' => 'popularity',
                                'from' => $date,
                                'to' => $date,
                            ]
                        );

        return $response->json();

    }

    public function insertData( array $data, int $categoryId ){

        if( isset($data['status']) && $data['status'] === 'ok' ){

            collect($data['articles'])
            ->each(function($articleData) use($categoryId){

                $source = $articleData['source'];

                $author = null;
                if( !empty($articleData['author']) ){
                    $author = Author::updateOrCreate(
                        ['name' => $articleData['author'], 'website' => $source['name']],
                        []
                    );
                }

                $article = Article::create([
                    'title' => $articleData['title'],
                    'description' => $articleData['description'],
                    'content' => $articleData['content'] ?? '',
                    'url' => $articleData['url'],
                    'image_url' => $articleData['urlToImage'],
                    'published_at' => Carbon::parse($articleData['publishedAt']),
                    'author_id' => $author?->id,
                    'source_id' => static::NEWS_API_ID,
                ]);

                $article->categories()->attach($categoryId);

            });


        }



    }


}