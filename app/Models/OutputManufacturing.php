<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputManufacturing extends Model
{
    use HasFactory;
    protected $table = 'output_manufacturings';
    protected $primaryKey='id';
    protected $fillable = [
       'production_date'
    ];

     ############################## Begin Relations #############################


    public function input_manufacturing(){
        return $this->hasMany('App\Models\InputManufacturing', 'output_manufacturing_id', 'id');
    }

    public function detail_output_manufacturing(){
        return $this->hasMany('App\Models\OutputManufacturingDetails', 'output_manufacturing_id', 'id');
    }
}
