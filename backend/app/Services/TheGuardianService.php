<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Author;
use App\Models\NewsSource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TheGuardianService extends NewsService {

    public function fetch( string $category = 'business', $date, int $page = 1 ): mixed{

        $response = Http::timeout(60)->acceptJson()
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

    protected function getNewSource(): void{
        $this->newSource = NewsSource::find(3);
    }

    
    public function getCategory( $category, $date, $page = 1 ){

        $response = $this->fetch( Str::lower($category->name), $date, $page );
        $data = $response['response'] ?? null;

        $this->insertData( $data, $category->id );
        
        if( isset($data['status']) && $data['status'] === 'ok' && $data['currentPage'] < $data['pages'] ){
            $this->getCategory( $category, $date, ++$page );
        }

    }

    public function insertData( ?array $data, int $categoryId ){

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
                    'url' => $articleData['webUrl'] ?? '',
                    'image_url' => $articleData['fields']['thumbnail'] ?? '',
                    'published_at' => Carbon::parse($articleData['webPublicationDate']),
                    'author_id' => $author?->id,
                    'source_id' => $this->newSource->id,
                ]);

                $article->categories()->attach($categoryId);

            });

        }

    }

}