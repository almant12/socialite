<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthSocialiteController extends Controller
{


    public function redirect(){
        $redirect_url = Socialite::driver('github')->stateless()->redirect()->getTargetUrl();
        return $redirect_url;
    }


    public function callback(Request $request) {
        $token = $request->query('code');
        
        // Merrni informacionin e përdoruesit
        try {
            $githubUser = Socialite::driver('github')->stateless()->userFromToken($token);
    
            Log::info('user', $githubUser->toArray());
    
            // Krijoni ose regjistroni përdoruesin
            $user = User::updateOrCreate([
                'email' => $githubUser->email,
            ], [
                'name' => $githubUser->name,
                'email' => $githubUser->email,
            ]);
    
            Auth::login($user);
    
            // Kthejeni përgjigjen
            return response()->json($user);
        } catch (\Exception $e) {
            Log::error('GitHub authentication error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Authentication failed'], 401);
        }
    }
    public function authUser(){
        dd(auth('sanctum')->user());
    }
}
