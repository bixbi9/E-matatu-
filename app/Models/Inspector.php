<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspector extends Model
{
    protected $table = 'inspectors';

    protected $fillable = [
        'first_name',
        'last_name',
        'role_id',
        'phone_number',
        'password',
        'status'
    ];

    protected $hidden = [
        'password'
    ];

    public $timestamps = false;

    protected $primaryKey = 'goverment_id';
}
