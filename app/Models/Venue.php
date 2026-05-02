<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToOrganizer;

class Venue extends Model
{
    use SoftDeletes, BelongsToOrganizer, HasFactory;

    protected $fillable = [
        'organizer_id',
        'name',
        'address',
        'city',
        'country',
        'max_capacity',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }
}
