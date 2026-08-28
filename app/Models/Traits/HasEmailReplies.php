<?php

namespace App\Models\Traits;

use App\Models\EmailReply;

trait HasEmailReplies
{
    /**
     * Email replies staff have sent to this lead, newest first.
     */
    public function emailReplies()
    {
        return $this->morphMany(EmailReply::class, 'repliable')->latest();
    }
}
