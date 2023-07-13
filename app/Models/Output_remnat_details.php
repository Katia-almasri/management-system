<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Output_remnat_details extends Model
{
    use HasFactory;
    protected $table = 'output_remnat_details';
    protected $primaryKey='id';
    protected $fillable = [
        'output_slaughter_id',
        'output_manufacturing_id',
        'output_cutting_id',
        'weight',
        'type_remant_id',
    ];

     ############################## Begin Relations #############################

    public function type_remnat(){
        return $this->belongsTo('App\Models\RemnantsType', 'type_remant_id', 'id');
    }

    public function output_slaughter(){
        return $this->belongsTo('App\Models\outPut_SlaughterSupervisor_table', 'output_slaughter_id', 'id');
    }

    public function output_cutting(){
        return $this->belongsTo('App\Models\output_cutting', 'output_cutting_id', 'id');
    }

    public function output_manufacturing(){
        return $this->belongsTo('App\Models\OutputManufacturing', 'output_manufacturing_id', 'id');
    }

    public function remnat_detail(){
        return $this->hasOne('App\Models\RemnatDetail', 'output_remnat_det_id', 'id');
    }

}
