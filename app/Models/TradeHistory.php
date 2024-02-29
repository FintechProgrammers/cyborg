<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeHistory extends Model
{
    use HasFactory, GeneratesUuid;

    protected $guarded = [];

    function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    function exchange()
    {
        return $this->belongsTo(Exchange::class, 'exchange_id');
    }

    /**
     * Define the route model binding key for a given model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('uuid', $value)->firstOrFail();
    }

    public function scopeFilterTrades($query, $username = null, $exchange = null, $tradeType = null, $tradeStatus = null)
    {
        return $query->when(!empty($username), function ($query) use ($username) {
            return $query->whereHas('user', function ($userQuery) use ($username) {
                $userQuery->where('users.username', 'like', "%$username%");
            });
        })
            ->when(!empty($exchange), function ($query) use ($exchange) {
                return $query->where('exchange_id', $exchange);
            })
            ->when(!empty($tradeType), function ($query) use ($tradeType) {
                return $query->where('trade_type', $tradeType);
            });
    }
}
