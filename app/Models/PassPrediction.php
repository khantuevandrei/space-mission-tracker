<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'satellite_id',
    'user_id',
    'city',
    'latitude',
    'longitude',
    'rise_time',
    'culmination_time',
    'set_time',
    'max_elevation',
    'notified'
])]
class PassPrediction extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function satellite()
    {
        return $this->belongsTo(Satellite::class);
    }
}
