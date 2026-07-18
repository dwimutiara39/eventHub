<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'title', 'message', 'is_read', 'read_at'])]
class Notification extends Model
{
    protected $table = 'notifications';
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the user this notification belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
