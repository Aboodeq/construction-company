<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailReply extends Model
{
    protected $fillable = [
        'repliable_type',
        'repliable_id',
        'sender_id',
        'to_email',
        'to_name',
        'subject',
        'body',
    ];

    public function repliable()
    {
        return $this->morphTo();
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
}
