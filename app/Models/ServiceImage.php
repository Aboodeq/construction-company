<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ServiceImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id',
        'image_path',
        'alt_text',
        'order',
    ];

    /**
     * The service this image belongs to.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
