<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'slug', 'description', 'start_time', 'end_time', 'location', 'capacity', 'banner', 'status', 'organization_id', 'category_id'])]
class Event extends Model
{
    /**
     * Get the category of this event.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the organization hosting this event.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get all registrations for this event.
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the speakers for this event.
     */
    public function speakers()
    {
        return $this->hasMany(Speaker::class);
    }

    /**
     * Get the schedules for this event.
     */
    public function schedules()
    {
        return $this->hasMany(EventSchedule::class)->orderBy('start_time');
    }

    /**
     * Get the sponsors for this event.
     */
    public function sponsors()
    {
        return $this->hasMany(Sponsor::class);
    }

    /**
     * Get the feedbacks for this event.
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}
