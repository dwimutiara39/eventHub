<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug'])]
class Category extends Model
{
    /**
     * Get all events under this category.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
