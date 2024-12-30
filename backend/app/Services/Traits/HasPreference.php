<?php

namespace App\Services\Traits;


trait HasPreference {

    public function filterByUserPreference( $query, $user ){
        
        $sources = $user->sources()->pluck('id');
        $authors = $user->authors()->pluck('id');
        $categories = $user->categories()->pluck('id');


        $query->whereIn('author_id', $authors)
                ->whereIn('source_id', $sources)
                ->whereHas('categories', function($hasQuery) use($categories){
                    $hasQuery->whereIn('categories.id', $categories);
                });

    }

}