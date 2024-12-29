<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;

abstract class NewsService {

    protected const NEWS_API_ID = 1;
    protected const NEW_YORK_TIMES_ID = 2;
    protected const THE_GUARDIAN_ID = 3;

    public function __invoke(){

        Category::all()
            ->each(fn($category) => $this->getCategory( $category ) );

    }

    public function getCategory( $category, $page = 1 ){
        $this->insertData( $this->fetch( Str::lower($category->name), $page ), $category->id );
    }


    abstract public function fetch( string $category, int  $page ): mixed;

    abstract public function insertData( array $data, int $categoryId );


}