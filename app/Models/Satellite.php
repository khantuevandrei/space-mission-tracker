<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Laravel\Scout\Searchable;
use Override;

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
    'latitude',
    'longitude',
    'last_tracked_at'
])]
class Satellite extends Model
{
    use Searchable;

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'type' => $this->type,
            'orbit_type' => $this->orbit_type,
            'status' => $this->status,
        ];
    }

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
