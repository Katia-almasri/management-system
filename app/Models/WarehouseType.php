<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseType extends Model
{
    use HasFactory;
    protected $table = 'warehouse_types';
    protected $primaryKey='id';
    protected $fillable = [
       'warehouse_name',
    ];

    

}
