<?php

namespace App\Services;

class TransactionService
{
    /**
     * Get the transaction action from the table column `action`.
     *
     * @param  string  $type
     * @return object $obj
     */
    public function type($type)
    {
        switch ($type) {
            case 'credit':
                $name = 'Credit';
                $class = 'badge bg-success';
                break;
            case 'debit':
                $name = 'Debit';
                $class = 'badge bg-danger';
                break;
            default:
                $name = 'Unknown';
                $class = 'badge bg-info';
        }

        return (object) [
            'name'      => $name,
            'class'     => $class,
        ];
    }

    /**
     * Get the transaction status from the table column `type`.
     *
     * @param  string  $status
     * @return object $obj
     */
    public function status($status)
    {
        switch ($status) {
            case 'complete':
                $name = 'Completed';
                $class = 'badge bg-success';
                break;
            case 'pending':
                $name = 'Pending';
                $class = 'badge bg-warning';
                break;
            case 'failed':
                $name = 'Failed';
                $class = 'badge bg-danger';
                break;
            default:
                $name = 'Unknown';
                $class = 'badge bg-info';
        }

        return (object) [
            'name'      => $name,
            'class'     => $class,
        ];
    }

    /**
     * Get the transaction status from the table column `type`.
     *
     * @param  string  $status
     * @return object $obj
     */
    public function action($action)
    {
        switch ($action) {
            case 'withdrawal':
                $name = 'Withdrawal';
                $class = 'badge bg-danger';
                break;
            case 'deposit':
                $name = 'Deposit';
                $class = 'badge bg-success';
                break;
            case 'transfer':
                $name = 'Transfer';
                $class = 'badge bg-warning';
                break;
            default:
                $name = 'Unknown';
                $class = 'badge bg-info';
        }

        return (object) [
            'name'      => $name,
            'class'     => $class,
        ];
    }
}
