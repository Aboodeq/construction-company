<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceFaq extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id',
        'question',
        'answer',
        'order',
    ];

    /**
     * The service this FAQ belongs to.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
