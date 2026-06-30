<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'name',
    'norad_id',
    'country',
    'type',
    'status',
    'tle_line1',
    'tle_line2',
    'orbit_type',
    'altitude_km',
    'velocity_kms',
    'inclination',
    'location',
    'last_tracked_at'
])]
class Satellite extends Model
{
    public function missions()
    {
        return $this->hasMany(Mission::class);
    }

    public function passPredictions()
    {
        return $this->hasMany(PassPrediction::class);
    }

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }
}
