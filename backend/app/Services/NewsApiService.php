<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Author;
use App\Models\NewsSource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewsApiService extends NewsService {

    public function fetch( string $category = 'business', $date, int $page ): mixed {

        $response = Http::timeout(60)->acceptJson()
                        ->get(
                            "https://newsapi.org/v2/top-headlines",
                            [
                                'category' => $category,
                                'apiKey' => config('app.news_api_key'),
                                'sortBy' => 'popularity',
                                'from' => $date,
                                'to' => $date,
                                'page' => $page,
                                'pageSize' => 100,
                            ]
                        );

        return $response->json();

    }

    public function getCategory( $category, $date, $page = 1 ){

        $data = $this->fetch( Str::lower($category->name), $date, $page );

        $this->insertData( $data, $category->id );
        
        if( isset($data['status']) && $data['status'] === 'ok' && $data['totalResults'] > intval($page * 100 ) ){
            $this->getCategory( $category, $date, ++$page );
        }

    }

    protected function getNewSource(): void{
        $this->newSource =  NewsSource::find(1);
    }


    public function insertData( array $data, int $categoryId ){
        
        logger($data);

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
                    'source_id' => $this->newSource->id,
                ]);

                $article->categories()->attach($categoryId);

            });


        }



    }


}