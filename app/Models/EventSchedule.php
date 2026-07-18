<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'start_time', 'end_time', 'event_id'])]
class EventSchedule extends Model
{
    /**
     * Get the event this schedule belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
