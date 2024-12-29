<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewYorkTimesService extends NewsService {


    public function fetch( string $category = 'business', int $page ): mixed{

        $date = Carbon::yesterday()->format('Y-m-d');

        $response = Http::acceptJson()
                        ->get(
                            "https://api.nytimes.com/svc/search/v2/articlesearch.json?",
                            [
                                'fq' => 'news_desk:("' . $category . '") AND pub_date:("'. $date .'")',
                                'api-key' => config('app.ny_api_key', ''),
                            ]
                        );

        return $response->json();

    }


    public function insertData( array $data, int $categoryId ){

        if( isset($data['status']) && $data['status'] === 'OK' && !empty($data['response']['docs']) ){

            collect($data['response']['docs'])
            ->each(function($doc) use($categoryId){

                $author = null;
                if( !empty($doc['byline']['person']) ){
                    $author = Author::updateOrCreate(
                        [
                            'name' =>$doc['byline']['person'][0]['firstname'] . ' ' . $doc['byline']['person'][0]['lastname'],
                            'website' => $doc['source']
                        ],
                        []
                    );
                }

                $imageUrl = '';
                if ( !empty($doc['multimedia'])) {
                    $imageUrl = 'https://static01.nyt.com/' . $doc['multimedia'][0]['url'];
                }

                $article = Article::create([
                    'title' => $doc['abstract'],
                    'description' => $doc['snippet'],
                    'content' => $doc['lead_paragraph'] ?? '',
                    'url' => $doc['web_url'],
                    'image_url' => $imageUrl,
                    'published_at' => Carbon::parse($doc['pub_date']),
                    'author_id' => $author?->id,
                    'source_id' => static::NEW_YORK_TIMES_ID,
                ]);

                $article->categories()->attach($categoryId);

            });




        }

    }


}