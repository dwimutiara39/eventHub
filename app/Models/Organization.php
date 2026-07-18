<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'description', 'logo', 'user_id'])]
class Organization extends Model
{
    /**
     * Get the user (admin) that manages this organization.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
