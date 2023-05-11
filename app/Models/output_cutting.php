<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class output_cutting extends Model
{
    use HasFactory;
    protected $table = 'output_cuttings';
    protected $primaryKey='id';
    protected $fillable = [
       'production_date'
    ];

     ############################## Begin Relations #############################


    public function input_cutting(){
        return $this->hasMany('App\Models\InputCutting', 'output_citting_id ', 'id');
    }

    public function detail_output_cutiing(){
        return $this->hasMany('App\Models\output_cutting_detail', 'output_cutting_details', 'id');
    }


}
