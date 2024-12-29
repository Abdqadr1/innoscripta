<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleService {

    public function __construct(
    ){}

    public function list(Request $request){

        $user = $request->user();
        $sources = $user->sources()->pluck('id');
        $authors = $user->authors()->pluck('id');
        $categories = $user->categories()->pluck('id');

        $query = Article::query();

        if( $authors->isNotEmpty() ){
            $query->whereIn('author_id', $authors);
        }

        if( $sources->isNotEmpty() ){
            $query->whereIn('source_id', $sources);
        }

        if( $categories->isNotEmpty() ){
            $query->whereHas('categories', function($hasQuery)use($categories){
                $hasQuery->whereIn('categories.id', $categories);
            });
        }

        return $query->paginate(20);

    }
    

    public function filter( Request $request ) {

        $category = $request->query('category');
        $source = $request->query('source');
        $date = $request->query('date');

        logger($category);
        logger($source);
        logger($date);

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