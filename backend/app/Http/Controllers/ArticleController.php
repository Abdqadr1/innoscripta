<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\User;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function __construct(
        public ArticleService $articleService
    ){}
    

    /**
     * Article feed
     * @unauthenticated
     */
    public function news_feed( Request $request ){


        return ArticleResource::collection(
            $this->articleService->list($request)
        );
        
    }


    /**
     * Filter article
     * @unauthenticated
     */
    public function filter( Request $request ){

        
        return ArticleResource::collection(
            $this->articleService->filter( $request )
        );
        
    }

}
