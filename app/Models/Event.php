<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToOrganizer;
use App\Enums\EventStatus;

class Event extends Model
{
    use SoftDeletes, BelongsToOrganizer, HasFactory;

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
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=400';
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return \Illuminate\Support\Facades\Storage::url($this->image);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
