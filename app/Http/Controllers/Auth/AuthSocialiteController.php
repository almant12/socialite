<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use App\Models\SocialAccount;
use App\Service\ImageUploadService;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class AuthSocialiteController extends Controller
{

    public ImageUploadService $imageUpload;

    public function __construct(ImageUploadService $imageUploadService){
        $this->imageUpload = $imageUploadService;
    }



    public function githubRedirect(){
        $redirect_url = Socialite::driver('github')->stateless()->redirect();
        return $redirect_url;
    }


    public function githubCallback() {

        $githubUser = Socialite::driver('github')->stateless()->user();

        dd($githubUser);

        $socialAccount = SocialAccount::where('provider_id',$githubUser->getId())
        ->where('provider_name','github')
        ->first();

        if($socialAccount){
            $socialAccount->update([
                'token'=>$githubUser->token,
            ]);
        }else{
            $user = User::updateOrCreate([
                'email'=>$githubUser->getAvatar()
            ]);
        }
    }
}
