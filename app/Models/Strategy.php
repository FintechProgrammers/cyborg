<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    use HasFactory, GeneratesUuid;

    protected $guarded = [];

    /**
     * Define the route model binding key for a given model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id');
    }

    const MARKETTYPE = [
        'spot' => 'spot',
        'future' => 'future',
    ];

    const STRATEGYMODE = [
        'short' => 'short',
        'long'  => 'long',
    ];
}
