<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sellingortype extends Model
{
    use HasFactory;

    protected $table = 'sellingortypes';
    protected $primaryKey='id';
    protected $fillable = [
       'name'
    ];

}
