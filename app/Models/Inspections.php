<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspections extends Model
{
    protected $connection = 'supabase';

    protected $table = 'inspections';
    protected $primaryKey = 'inspection_id';
    public $timestamps = false;

    protected $fillable = [
        'vehicle_id',
        'inspector_name',
        'result',
        'comments',
        'rating',
        'status',
        'inspection_date',
        'evaluation_form',
        'maintenance_type',
    ];
}
