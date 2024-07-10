<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    protected $table = 'Routes';
    protected $primaryKey = 'route_id';
    protected $fillable = [

    'route_id', 
    'start_location',
    'end_location',
    'distance',
    'estimated_time',
    'status',
    'driver_id',
     
    ];


}
