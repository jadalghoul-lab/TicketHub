<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'venue_id',
        'name',
        'capacity',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }
}
