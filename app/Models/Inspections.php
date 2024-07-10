<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspections extends Model
{
    use HasFactory;
    protected $table = 'Inspections';
    protected $primaryKey = 'inspection_id';
    protected $fillable = [

    'vehicle_id',
    'inspector name',
    'result',
    'comments',
    'rating' ,
    'status',
    'inspection date',
    'evaluation form',
    'maintenance type'
     
    ];


}
