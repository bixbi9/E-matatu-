<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $connection = 'supabase';

    protected $table = 'maintenance';

    protected $fillable = [
        'vehicle_id',
        'date',
        'description',
        'cost',
        'maintenance_type',
        'status'
    ];

    public $timestamps = false;

    protected $primaryKey = 'maintenance_id';
}
