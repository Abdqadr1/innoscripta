<?php

namespace App\Http\Controllers;

use App\Helpers\Constants\ValidationConstant;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\NewsSourceResource;
use App\Http\Resources\UserResource;
use App\Models\Author;
use App\Models\Category;
use App\Models\NewsSource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        public UserService $userService,
    ){}

    /**
     * Get user preference
     * @unauthenticated
     */
    public function getPreferences(Request $request){

        $user = $request->user();

        $user->loadMissing('authors', 'sources', 'categories');

        return [
            'preferences' => [
                'authors' => $user->authors,
                'categories' => $user->categories,
                'sources' => $user->sources,
            ],
            'authors' => AuthorResource::collection( Author::all() ),
            'categories' => CategoryResource::collection( Category::all() ),
            'sources' => NewsSourceResource::collection( NewsSource::all() ),
        ];

    }


    /**
     * Set user preference
     * @unauthenticated
     */
    public function setPreferences(Request $request){

        $validated = $request->validate([
            'authors' => ValidationConstant::ARRAY,
            'authors.*' => ValidationConstant::EXISTS_IN('authors'),
            'categories' => ValidationConstant::ARRAY,
            'categories.*' => ValidationConstant::EXISTS_IN('categories'),
            'sources' => ValidationConstant::ARRAY,
            'sources.*' => ValidationConstant::EXISTS_IN('news_sources'),
        ]);

        $user = $request->user();

        $user->authors()->sync( $validated['authors'] );
        $user->categories()->sync( $validated['categories'] );
        $user->sources()->sync( $validated['sources'] );

        $user->loadMissing('authors', 'sources', 'categories');
        return new UserResource( $user );
    }


    /**
     * Register new user
     * @unauthenticated
     */
    public function register( Request $request ){

        $validated = $request->validate([
            'name' => ValidationConstant::VAR_CHAR,
            'email' => ValidationConstant::EMAIL_UNIQUE,
            'password' => ValidationConstant::PASSWORD
        ]);

        $user = $this->userService->addUser( $validated );
        $token =  $user->createToken( $request->input('device_name', $user->email ) )->plainTextToken;

        return [
            'token' => $token,
            'user' => new UserResource( $user ),
        ];
        
    }


    

    /**
     * Toggle user is_preference flag
     */
    public function toggle(Request $request) {
        $user = $request->user();
        
        $user->update([
            'is_preference' => !$user->is_preference
        ]);

        return new UserResource( $user->fresh() );
        
    }
}
