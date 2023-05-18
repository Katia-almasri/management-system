<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    use HasFactory;

    protected $table = 'directions';
    protected $primaryKey='id';
    protected $fillable = [
       'section',
       'to'
    
    ];

}
