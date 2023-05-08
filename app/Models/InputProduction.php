<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputProduction extends Model
{
    use HasFactory;

    protected $table = 'input_productions';
    protected $primaryKey='id';
    protected $fillable = [
       'type_id',
       'weight',
       'income_date',
       'output_date',
       'weight_detail_id'
    ];


    public function inputSlaughter(){
        return $this->belongsTo('App\Models\input_slaughter_table', 'productionId', 'id');
    }

    public function weightAfterArrivalDetection(){
        return $this->belongsTo('App\Models\weightAfterArrivalDetection', 'weight_after_libra_id', 'id');
    }
}
