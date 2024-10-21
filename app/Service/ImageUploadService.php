<?php 

namespace App\Service;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


 class ImageUploadService{


    public function uploadImage(Request $request,$filename,$path): string{

        if($request->hasFile($filename)){
            $image = $request->{$filename};
            $ext = $image->getClientOriginalExtension();
            $imageName = 'image_'.uniqid().'.'.$ext;
            $pathImage = $image->storeAs($path,$imageName,'public');

            return 'storage/'.$pathImage;
        }
    }

    public function saveImageFromUrl($urlImage, $path):string
    {
        try {
            $imageResponse = Http::get($urlImage);
    
            if ($imageResponse->successful()) {
                // Get the image contents as a string
                $imageContents = $imageResponse->body();
                
                // Create a unique image name
                $imageName = 'image_' . uniqid() . '.jpg';
    
                // Save the image using Laravel's storage facade
                Storage::disk('public')->put($path . '/' . $imageName, $imageContents);
    
                return 'storage/' . $path . '/' . $imageName;
            } else {
                throw new \Exception('Failed to download image from URL');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function updateImage(Request $request,$filename,$oldPath,$path): string{

        if($request->hasFile($filename)){
            if(File::exists($oldPath)){
                File::delete($oldPath);
            }

            $image = $request->{$filename};
            $ext = $image->getClientOriginalExtension();
            $imageName = 'image_'.uniqid().'.'.$ext;
            $pathImage = $image->storeAs($path,$imageName,'public');

            return 'storage/'.$pathImage;
        }
    }

    public function deleteImage($path):void{
        if(File::exists(public_path($path))){
            File::delete(public_path($path));
        }
    }
 }