<?php

namespace App\Services\Traits;


trait HasPreference {

    public function filterByUserPreference( $query, $user ){
        
        $sources = $user->sources()->pluck('id');
        $authors = $user->authors()->pluck('id');
        $categories = $user->categories()->pluck('id');


        $query->orWhereIn('author_id', $authors)
                ->orWhereIn('source_id', $sources)
                ->orWhereHas('categories', function($hasQuery) use($categories){
                    $hasQuery->whereIn('categories.id', $categories);
                });

    }

}