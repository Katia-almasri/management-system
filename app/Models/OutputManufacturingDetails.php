<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputManufacturingDetails extends Model
{
    use HasFactory;
    protected $table = 'output_manufacturing_details';
    protected $primaryKey='id';
    protected $fillable = [
       'type_id',
       'expiry_date',
       'weight',
       'output_manufacturing_id'
    ];

    public function outputTypes(){
        return $this->belongsTo('App\Models\outPut_SlaughterSupervisorType_table', 'type_id', 'id');
    }

    public function detail_output_manufacturing(){
        return $this->belongsTo('App\Models\OutputManufacturing', 'output_manufacturing_id', 'id');
    }

}
