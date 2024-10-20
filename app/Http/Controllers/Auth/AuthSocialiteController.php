<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthSocialiteController extends Controller
{


    public function redirect(){
        $redirect_url = Socialite::driver('github')->stateless()->redirect()->getTargetUrl();
        return $redirect_url;
    }


    public function callback(Request $request) {
         //take the code from callback
         $code = $request->input('code');

         try{

            //pass the code to this authorize api
            $response = Http::asForm()->post('https://github.com/login/oauth/access_token',[
                'client_id'=>env('GITHUB_CLIENT_ID'),
                'client_secret'=>env('GITHUB_CLIENT_SECRET'),
                'code'=>$code
            ]);

            $accessTokenData = $response->json();

            //check for accessToken
            if(!isset($accessTokenData['access_token'])){
                return response()->json(['error'=>'Authenticate failed'],500);
            }

            $token = $accessTokenData['access_token'];

            return $token;

        }catch (Exception $e){
            return $e;
        }
    }
}
