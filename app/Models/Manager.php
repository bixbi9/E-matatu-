<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    protected $connection = 'supabase';

    protected $table = 'managers';

    protected $fillable = [
        'first_name',
        'last_name',
        'role_id',
        'phone_number',
        'password',
        'status',
        'comments'
    ];

    protected $hidden = [
        'password'
    ];

    public $timestamps = false;

    protected $primaryKey = 'manager_id';
}
