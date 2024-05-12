<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverProfile extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'real_name',
        'vehicle_type',
        'license_plate_number',
        'maximum_passengers',
        'special_vehicle_info',
        'is_driver',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($driverProfile) {
            // The "created" method is similar to the "post_save" signal in Django
            // You can put your logic here
        });
    }
}