<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organizer extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'logo',
        'website',
        'slug',
        'contact_email',
        'payout_email',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
