<?php

namespace App\Services;

use App\Models\Category;
use App\Models\NewsSource;
use Carbon\Carbon;
use Illuminate\Support\Str;

abstract class NewsService {

    protected NewsSource $newSource;

    public function __invoke(){

        $this->getNewSource();

        $date = Carbon::parse($this->newSource->last_fetched)->addDay();

        if( $date->lessThan( Carbon::today() ) ){

            Category::all()
                ->each(fn($category) => $this->getCategory( $category, $date->format('Y-m-d') ) );
    
            //update news source last fetched
            $this->newSource->update([
                'last_fetched' => $date
            ]);

        }

    }

    public function getCategory( $category, $date, $page = 1 ){
        $this->insertData( 
            $this->fetch( Str::lower($category->name), $date, $page ), 
            $category->id 
        );
    }

    abstract protected function getNewSource();

    abstract public function fetch( string $category, $date, int $page ): mixed;

    abstract public function insertData( array $data, int $categoryId );


}