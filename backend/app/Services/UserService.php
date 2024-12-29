<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\RegistrationNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService {

    public function __construct(
    ){}
    

    public function addUser( array $details ): User {
        

        $user = DB::transaction(function() use($details) {

            $details['password'] = Hash::make( $details['password'] );

            $user = User::create( $details );

            defer(function()use($user){
                $user->notifyNow( new RegistrationNotification() );
            });


            return $user;

        });


        return $user;

    }


}