<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuoteRequestFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'quote_request_id',
        'file_path',
        'type',
    ];

    /**
     * The quote request this file belongs to.
     */
    public function quoteRequest()
    {
        return $this->belongsTo(QuoteRequest::class);
    }
}
