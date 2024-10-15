<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthSocialiteController extends Controller
{


    public function redirect(){
        $redirect_url = Socialite::driver('github')->stateless()->redirect()->getTargetUrl();
        return $redirect_url;
    }


    public function callback(Request $request){

        $token = $request->query('code');
        

        $githubUser = Socialite::driver('github')->stateless()->user();

        // Add your logic to create or log in the user here
        $user = User::updateOrCreate([
            'email' => $githubUser->email,
        ], [
            'name' => $githubUser->name,
            'email' => $githubUser->email,
        ]);

        Auth::login($user);

        // response
        return response()->json($user);
    }
}
