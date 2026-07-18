<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'title', 'company', 'photo_url', 'event_id'])]
class Speaker extends Model
{
    /**
     * Get the event this speaker belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
