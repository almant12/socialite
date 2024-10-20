<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class AuthSocialiteController extends Controller
{


    public function redirect(){
        $redirect_url = Socialite::driver('github')->stateless()->redirect();
        return $redirect_url;
    }


    public function callback() {

        $gitHubUser = Socialite::driver('github')->stateless()->user();

        $user = User::updateOrCreate([
            'id' => $gitHubUser->getId(),
        ],[
            'name'=> $gitHubUser->getName(),
            'email'=> $gitHubUser->getEmail(),
            'avatar'=> $gitHubUser->getAvatar()
        ])

    }
}
