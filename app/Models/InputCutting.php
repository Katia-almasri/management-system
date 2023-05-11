<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputCutting extends Model
{
    use HasFactory;

    protected $table = 'input_cuttings';
    protected $primaryKey='id';
    protected $fillable = [
       'weight',
       'income_date',
       'output_date',
       'cutting_done',
       'output_slaughter_det_Id'
    ];


    public function output_detail_SlaughterSupervisor(){
        return $this->BelongsTo('App\Models\DirectToOutputSlaughter', 'direct_to_output_slaughters_id', 'id');
    }
}
