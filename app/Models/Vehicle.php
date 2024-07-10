<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicles';

    protected $fillable = [
        'license_plate',
        'maintenance_status',
        'inspection_date',
        'vin',
        'color',
        'status',
        'current_driver_id'
    ];

    public $timestamps = false;

    protected $primaryKey = 'vehicle_id';
}
