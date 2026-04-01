<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $connection = 'supabase';

    protected $table = 'insurance';

    protected $fillable = [
        'vehicle_id',
        'policy_number',
        'provider',
        'start_date',
        'expiry_date',
        'coverage_details'
    ];

    public $timestamps = false;

    protected $primaryKey = 'insurance_id';
}
