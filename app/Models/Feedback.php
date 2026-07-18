<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'event_id', 'rating', 'review'])]
class Feedback extends Model
{
    protected $table = 'feedbacks';
    /**
     * Get the user who provided the feedback.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event this feedback belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
