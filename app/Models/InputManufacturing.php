<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputManufacturing extends Model
{
    use HasFactory;

    protected $table = 'input_manufacturings';
    protected $primaryKey='id';
    protected $fillable = [
       'weight',
       'income_date',
       'output_date',
       'manufacturings_done',
       'output_slaughter_det_Id',
       'output_cutting_det_Id',
       'type_id'
    ];


    public function output_detail_SlaughterSupervisor(){
        return $this->BelongsTo('App\Models\outPut_SlaughterSupervisor_detail', 'output_slaughter_det_Id', 'id');
    }

    public function output_detail_cutting(){
        return $this->BelongsTo('App\Models\output_cutting_detail', 'output_cutting_det_Id', 'id');
    }
}
