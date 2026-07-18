<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'event_id', 'status', 'is_attended'])]
class Registration extends Model
{
    /**
     * Get the user who registered.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event the user registered for.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the certificate for this registration.
     */
    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }
}
