<?php

namespace App\Http\Controllers;

use App\Helpers\Constants\ValidationConstant;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    

    /**
     * Get api token
     * @unauthenticated
     */
    public function login (Request $request) {

        $request->validate([
            'email' => ValidationConstant::EMAIL,
            'password' => ValidationConstant::PASSWORD_NO_CONFIRM
        ]);
     
        $user = User::where('email', $request->email)->first();
     
        if (! $user || !Hash::check( $request->password, $user->password ) ) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
     
        
        $token =  $user->createToken( $request->device_name ?? $user->email )->plainTextToken;
        $user->loadMissing('authors', 'sources', 'categories');

        return [
            'token' => $token,
            'user' => new UserResource( $user )
        ];
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request) {
        $user = $request->user();
        $user->loadMissing('authors', 'sources', 'categories');
        return new UserResource( $user );
    }


}
