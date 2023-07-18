<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    use HasFactory;
    protected $table = 'predictions';
    protected $primaryKey='id';
    protected $fillable = [
       'year_month',
       'expected_weight',
       'output_type'
    ];


}
