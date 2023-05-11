<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectToOutputSlaughter extends Model
{
    use HasFactory;

    protected $table = 'direct_to_output_slaughters';
    protected $primaryKey='id';
    protected $fillable = [
       'output_det_s_id',
       'weight',
       'by_section'
    ];

     ############################## Begin Relations #############################
     public function detonatorFrigeDetail(){
        return $this->belongsTo('App\Models\outPut_SlaughterSupervisor_detail', 'output_det_s_id', 'id');
    }

    public function inputCutting(){
        return $this->hasOne('App\Models\InputCutting', 'direct_to_output_slaughters_id', 'id');
    }

    // public function InputManufacturing(){
    //     return $this->hasOne('App\Models\InputManufacturing', 'output_slaughter_det_Id', 'id');
    // }
}
