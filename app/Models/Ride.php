<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'destination',
        'pickup_longitude',
        'pickup_latitude',
        'destination_latitude',
        'destination_longitude',
        'required_arrival_time',
        'passenger_number_from_owner',
        'passenger_number_in_total',
        'ride_status',
        'requested_vehicle_type',
        'special_request',
        'can_be_shared',
        'sharer_id_and_passenger_number_pair',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function sharers()
    {
        return $this->belongsToMany(User::class, 'ride_user', 'ride_id', 'user_id');
    }
}