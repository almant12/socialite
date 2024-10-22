<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use App\Models\SocialAccount;
use App\Service\ImageUploadService;
use App\Http\Controllers\Controller;
use Exception;
use Laravel\Socialite\Facades\Socialite;

class AuthSocialiteController extends Controller
{

    public ImageUploadService $imageUpload;

    public function __construct(ImageUploadService $imageUploadService){
        $this->imageUpload = $imageUploadService;
    }



    public function githubRedirect(){
        $redirect_url = Socialite::driver('github')->stateless()->redirect()->getTargetUrl();
        return $redirect_url;
    }


    public function githubCallback()
{
    try {
        $githubUser = Socialite::driver('github')->stateless()->user();

        // Check if a social account already exists for this provider
        $socialAccount = SocialAccount::where('provider_id', $githubUser->getId())
            ->where('provider_name', 'github')
            ->first();

        if ($socialAccount) {
            // Get the associated user
            $user = $socialAccount->user;

            // Update the user details with GitHub credentials
            $user->update([
                'name' => $githubUser->getName(),
                'email' => $githubUser->getEmail(),
                'email_verified_at' => now(),
                'avatar' => $this->imageUpload->saveImageFromUrl($githubUser->getAvatar(), 'images')
            ]);

            // Update the social account token
            $socialAccount->update([
                'token' => $githubUser->token,
            ]);

            // Generate a Sanctum token for the user
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'user'=>$user,
                'token'=>$token
            ]);
            
        } else {
            // Create a new user and social account if they don't exist
            $user = User::firstOrCreate(
                ['email' => $githubUser->getEmail()],
                [
                    'name' => $githubUser->getName(),
                    'email_verified_at' => now(),
                    'avatar' => $this->imageUpload->saveImageFromUrl($githubUser->getAvatar(), 'images')
                ]
            );

            SocialAccount::create([
                'provider_id' => $githubUser->getId(),
                'provider_name' => 'github',
                'token' => $githubUser->token,
                'user_id' => $user->id
            ]);

            // Generate a Sanctum token for the new user
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'user'=>$user,
                'token'=>$token
            ]);
        }
    } catch (\Exception $e) {
        // Handle errors and return a response with the error message
        return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
    }
}

    
}