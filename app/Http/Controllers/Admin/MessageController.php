<?php

namespace App\Http\Controllers\Admin;


use App\Models\Message;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    


    public function index():JsonResponse{

        $authUserId = Auth::user()->id;

        //fetch users who have sent us messages
        $senderUsers = Message::with('senderProfile')->select(['sender_id'])
        ->where('receiver_id',$authUserId)
        ->where('sender_id','!=',$authUserId)
        ->groupBy('sender_id');
        

        //fetch users that we have sent them messages
        $receiverUsers = Message::with('receiverProfile')->select(['receiver_id'])
        ->where('sender_id',$authUserId)
        ->where('receiver_id','!=',$authUserId)
        ->groupBy('receiver_id');
        

        // Combine both sender and receiver users
        $chatUsers = $senderUsers->union($receiverUsers)->get()->map(function($user) {
            return [
                'user' => [
                    'id' => $user->receiverProfile->id ?? $user->senderProfile->id,
                    'avatar' => $user->receiverProfile->avatar ?? $user->senderProfile->avatar,
                    'name' => $user->receiverProfile->name ?? $user->senderProfile->name,
                ],
            ];
        });

        return response()->json($chatUsers);
    }

    public function sendMessage(Request $request,string $id){
        
        $request->validate([
            'message'=>['required']
        ]);

    }


    public function getMessage(string $id):JsonResponse{

        $authUserId = Auth::user()->id;

        $messages = Message::whereIn('receiver_id',[$authUserId,$id])
        ->whereIn('sender_id',[$authUserId,$id])
        ->with(['senderProfile','receiverProfile'])
        ->orderBy('created_at', 'asc')
        ->get();

        return response()->json($messages);
    }
}
