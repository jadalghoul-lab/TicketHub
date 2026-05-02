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
        'refund_deadline',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'time' => 'datetime',
            'refund_deadline' => 'datetime',
            'status' => EventStatus::class,
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('status', EventStatus::PUBLISHED->value);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
