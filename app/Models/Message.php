<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $table = 'message';



    public function senderProfile():BelongsTo{
        return $this->belongsTo(User::class,'sender_id','id')->select(['id','avatar','name']);
    }

    public function receiverProfile():BelongsTo{
        return $this->belongsTo(User::class,'receiver_id','id')->select(['id','avatar','name']);
    }
}
