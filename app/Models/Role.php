<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';

    protected $fillable = [
        'description',
        'status'
    ];

    public $timestamps = false;

    protected $primaryKey = 'role_id';
    public $incrementing = false;
    protected $keyType = 'string';
}
