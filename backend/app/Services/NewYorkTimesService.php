<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Author;
use App\Models\NewsSource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewYorkTimesService extends NewsService {


    public function fetch( string $category = 'business', $date, int $page ): mixed{

        $response = Http::timeout(60)->acceptJson()
                        ->get(
                            "https://api.nytimes.com/svc/search/v2/articlesearch.json?",
                            [
                                'fq' => 'news_desk:("' . $category . '") AND pub_date:("'. $date .'")',
                                'api-key' => config('app.ny_api_key', ''),
                                'page' => $page
                            ]
                        );

        return $response->json();

    }

    protected function getNewSource(): void{
        $this->newSource = NewsSource::find(2);
    }



    public function insertData( array $data, int $categoryId ){

        logger($data);

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
                    'source_id' => $this->newSource->id,
                ]);

                $article->categories()->attach($categoryId);

            });




        }

    }


}