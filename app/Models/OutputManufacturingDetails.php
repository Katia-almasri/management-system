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
        return $this->belongsTo('App\Models\outPut_Type_Production', 'type_id', 'id');
    }

    public function detail_output_manufacturing(){
        return $this->belongsTo('App\Models\OutputManufacturing', 'output_manufacturing_id', 'id');
    }

    //morph outputable
    public function outputable(){
        return $this->morphTo();
    }
    //inputable from (det detaail 1, 2, 3,  zero frige detail)

    public function DetonatorFrige1Detail()
    {
        return $this->morphOne('App\Models\DetonatorFrige1Detail', 'inputable');
    }

    public function DetonatorFrige2Detail()
    {
        return $this->morphOne('App\Models\DetonatorFrige2Detail', 'inputable');
    }

    public function DetonatorFrige3Detail()
    {
        return $this->morphOne('App\Models\DetonatorFrige3Detail', 'inputable');
    }


}
