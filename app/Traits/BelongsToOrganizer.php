<?php

namespace App\Traits;

use App\Models\Organizer;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToOrganizer
{
    protected static function bootBelongsToOrganizer()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (auth()->hasUser() && auth()->user()->isOrganizer()) {
                $organizer = auth()->user()->organizer;
                if ($organizer) {
                    $model->organizer_id = $organizer->id;
                }
            }
        });
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }
}
