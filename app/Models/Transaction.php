<?php

namespace App\Models;

use App\Services\TransactionService;
use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory,GeneratesUuid;

    protected $guarded = [];


    function user()
    {
        return $this->belongsTo(User::class,'user_id');
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

     /**
     * Get the type of a single transaction record created.
     */
    public function action()
    {
        return (new TransactionService)->action($this->action);
    }

    public function type()
    {
        return (new TransactionService)->type($this->type);
    }

    /**
     * Get the status of a single transaction record created.
     */
    public function status()
    {
        return (new TransactionService)->status($this->status);
    }
}
