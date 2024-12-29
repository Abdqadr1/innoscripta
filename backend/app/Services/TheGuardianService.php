<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TheGuardianService extends NewsService {

    public function fetch( string $category = 'business', int $page = 1 ): mixed{

        $date = Carbon::parse("2024-12-27")->format('Y-m-d');

        logger("category: {$category}");

        $response = Http::acceptJson()
                        ->get(
                            "https://content.guardianapis.com/{$category}",
                            [
                                'api-key' => config('app.guardian_api_key', ''),
                                'from-date' => $date,
                                'to-date' => $date,
                                'page-size' => 50,
                                'show-tags' => 'contributor',
                                'show-fields' => 'headline,body,thumbnail,short-url',
                                'order-by' => 'relevance',
                                'page' => $page
                            ]
                        );

        return $response->json();

    }

    
    public function getCategory( $category, $page = 1 ){

        $response = $this->fetch( Str::lower($category->name), $page );
        $data = $response['response'];
        logger($data);

        $this->insertData( $data, $category->id );
        
        if( isset($data['status']) && $data['status'] === 'ok' && $data['currentPage'] < $data['pages'] ){
            $this->getCategory( $category, ++$page );
        }

    }

    public function insertData( array $data, int $categoryId ){

        if( isset($data['status']) && $data['status'] === 'ok' ){

            collect($data['results'])
            ->each(function($articleData) use($categoryId){

                $author = null;
                if( !empty($articleData['tags']) && !empty($articleData['tags'][0]) ){
                    $tag = $articleData['tags'][0];
                    $author = Author::updateOrCreate(
                        ['name' => $tag['webTitle'], 'website' => $tag['webUrl']],
                        []
                    );
                }

                $article = Article::create([
                    'title' => $articleData['fields']['headline'],
                    'description' => $articleData['fields']['headline'],
                    'content' => $articleData['fields']['body'] ?? '',
                    'url' => $articleData['webUrl'],
                    'image_url' => $articleData['fields']['thumbnail'],
                    'published_at' => Carbon::parse($articleData['webPublicationDate']),
                    'author_id' => $author?->id,
                    'source_id' => static::THE_GUARDIAN_ID,
                ]);

                $article->categories()->attach($categoryId);

            });


        }



    }

}