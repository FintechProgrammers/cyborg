<?php

namespace App\Models;

use App\Services\TransactionService;
use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, GeneratesUuid;

    protected $guarded = [];


    function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function scopeFilterTransactions($query, $transactionReference = null, $status = null, $action = null, $type = null,  $dateFrom = null, $dateTo = null)
    {
        return $query->when(!empty($transactionReference), function ($query) use ($transactionReference) {
            // From reference
            return $query->where('reference', $transactionReference);
        })
            ->when(!empty($status), function ($query) use ($status) {
                // From status
                return $query->where('status', $status);
            })
            ->when(!empty($action), function ($query) use ($action) {
                // From action
                return $query->where('action', $action);
            })
            ->when(!empty($type), function ($query) use ($type) {
                // From type
                return $query->where('type', $type);
            })
            ->when(!empty($dateFrom) && !empty($dateTo), function ($query) use ($dateFrom, $dateTo) {
                // From date range
                return $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            })->when(!empty($action) && !empty($status), function ($query) use ($action, $status) {
                // From action and status (eg Complted withdrawal)
                return $query->where('action', $action)->where('status', $status);
            })->when(!empty($action) && !empty($status) && !empty($dateFrom) && !empty($dateTo), function ($query) use ($action, $status, $dateFrom, $dateTo) {
                // From action, status and daterange  (eg Complted withdrawal from 12-01-2015 to 15-01-2015)
                return $query->where('action', $action)->where('status', $status)->whereBetween('created_at', [$dateFrom, $dateTo]);
            })->when(!empty($type) && !empty($dateFrom) && !empty($dateTo), function ($query) use ($type, $dateFrom, $dateTo) {
                // From action, status and daterange  (eg Debit from 12-01-2015 to 15-01-2015)
                return $query->where('type', $type)->whereBetween('created_at', [$dateFrom, $dateTo]);
            })->when(!empty($status) && !empty($dateFrom) && !empty($dateTo), function ($query) use ($status, $dateFrom, $dateTo) {
                // From status and daterange  (eg Complete from 12-01-2015 to 15-01-2015)
                return $query->where('status', $status)->whereBetween('created_at', [$dateFrom, $dateTo]);
            })->when(!empty($action) && !empty($dateFrom) && !empty($dateTo), function ($query) use ($action, $dateFrom, $dateTo) {
                // From status and daterange  (eg Deposit from 12-01-2015 to 15-01-2015)
                return $query->where('action', $action)->whereBetween('created_at', [$dateFrom, $dateTo]);
            })->when(!empty($action) && !empty($type) && !empty($status), function ($query) use ($action, $type, $status) {
                // From status and daterange  (eg Debit that are withdrawal and completed)
                return $query->where('type', $type)->where('action', $action)->where('status', $status);
            })->when(!empty($action) && !empty($type) && !empty($status) && !empty($dateFrom) && !empty($dateTo), function ($query) use ($action, $type, $status, $dateFrom, $dateTo) {
                // From status and daterange  (eg Debit that are withdrawal and completed)
                return $query->where('type', $type)->where('action', $action)->where('status', $status)->whereBetween('created_at', [$dateFrom, $dateTo]);
            });
    }
}
