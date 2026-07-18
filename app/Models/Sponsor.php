<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'tier', 'logo_url', 'event_id'])]
class Sponsor extends Model
{
    /**
     * Get the event this sponsor belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
