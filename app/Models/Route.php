<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    
    protected $connection = 'supabase';

    protected $table = 'routes';
    protected $primaryKey = 'route_id';
    public $timestamps = false;

    protected $fillable = [
        'route_id',
        'route_code',
        'route_name',
        'start_location',
        'end_location',
        'distance',
        'estimated_time',
        'status',
        'driver_id',
        'source_label',
    ];

}
