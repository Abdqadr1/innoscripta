<?php

namespace App\Services;

use App\Models\Article;
use App\Services\Traits\HasPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleService {

    use HasPreference;

    public function __construct(
    ){}

    public function list(Request $request){

        $user = $request->user();

        $use_preference = boolval( $request->query('use_preference', 0) );

        $query = Article::query();

        if( $use_preference ) $this->filterByUserPreference( $query, $user );

        return $query->paginate(20);

    }
    

    public function filter( Request $request ) {

        $category = $request->query('category');
        $source = $request->query('source');
        $date = $request->query('date');

        $query = Article::query();

        if( !empty($category) && $category != 'undefined' ){
            $query->whereHas('categories', function($hasQuery)use($category){
                $hasQuery->where('categories.id', $category);
            });
        }

        if( !empty($source) && $source != 'undefined' ){
            $query->where('source_id', $source);
        }

        if( !empty($date) && $date != 'undefined' ){
            $query->whereRaw("DATE(published_at) = ?", $source);
        }

        return $query->paginate(20);

    }


}