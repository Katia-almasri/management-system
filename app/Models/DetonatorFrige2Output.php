<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetonatorFrige2Output extends Model
{
    use HasFactory;

    protected $table = 'detonator_frige2_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'output_date',
       'weight',
       'amount',
       'det2_id'
    ];

    ################### Begin Relations #####################
     public function DetonatorFrige2InputOutput(){
        return $this->hasMany('App\Models\DetonatorFrige2InputOutput', 'output_id', 'id');
    }

    public function detonator2(){
        return $this->belongsTo('App\Models\DetonatorFrige2', 'det2_id', 'id');
    }


    //MORPH RELATIONSHIP BTN DETAILS AND(SLAUGHTER, .., .., SAWA3E8)
    public function outputable(){
        return $this->morphTo();
    }

    public function storeDetail()
    {
        return $this->morphOne('App\Models\StoreDetail', 'inputable');
    }

}
