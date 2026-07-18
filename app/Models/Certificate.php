<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['registration_id', 'certificate_url', 'issued_at'])]
class Certificate extends Model
{
    protected $casts = [
        'issued_at' => 'datetime',
    ];

    /**
     * Get the registration this certificate belongs to.
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
