<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutRequest extends Model
{
    protected $fillable = [
        'organizer_id',
        'amount',
        'status',
        'bank_details',
        'notes',
    ];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }
}
