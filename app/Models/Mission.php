<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'name',
    'agency',
    'type',
    'status',
    'launch_date',
    'launch_site',
    'rocket',
    'description',
    'satellite_id'
])]
class Mission extends Model
{
    public function satellite()
    {
        return $this->belongsTo(Satellite::class);
    }

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }
}
