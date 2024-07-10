<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'drivers';

    protected $fillable = [
        'first_name',
        'last_name',
        'license_number',
        'phone_number',
        'password',
        'status',
        'comments',
        'role_id'
    ];

    protected $hidden = [
        'password'
    ];

    public $timestamps = false;

    protected $primaryKey = 'driver_id';
}
