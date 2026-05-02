<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class TicketType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'zone_id',
        'name',
        'price',
        'quantity',
        'sales_start',
        'sales_end',
        'max_per_order',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
            'max_per_order' => 'integer',
            'sales_start' => 'datetime',
            'sales_end' => 'datetime',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class)->withoutGlobalScopes();
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
