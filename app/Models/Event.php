<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToOrganizer;
use App\Enums\EventStatus;

class Event extends Model
{
    use SoftDeletes, BelongsToOrganizer;

    protected $fillable = [
        'organizer_id',
        'venue_id',
        'title',
        'slug',
        'description',
        'image',
        'category',
        'start_date',
        'end_date',
        'time',
        'city',
        'country',
        'capacity',
        'status',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'time' => 'datetime',
            'status' => EventStatus::class,
        ];
    }
}
