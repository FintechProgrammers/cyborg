<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;

    protected $guarded = [];

    function exchange()
    {
        return $this->belongsTo(Exchange::class,'exchange_id');
    }
}
