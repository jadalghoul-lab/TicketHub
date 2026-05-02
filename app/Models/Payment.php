<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'stripe_payment_id',
        'amount',
        'currency',
        'status',
        'payment_method',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
