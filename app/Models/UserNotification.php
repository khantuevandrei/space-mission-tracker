<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id',
    'satellite_id',
    'mission_id',
    'type',
    'email',
    'telegram',
])]
class UserNotification extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function satellite()
    {
        return $this->belongsTo(Satellite::class);
    }

    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }
}
