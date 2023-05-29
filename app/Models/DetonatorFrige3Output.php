<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetonatorFrige3Output extends Model
{
    use HasFactory;

    protected $table = 'detonator_frige3_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'output_date',
       'weight',
       'amount',
       'det3_id',
       'output_to'
    ];

    ################### Begin Relations #####################
     public function DetonatorFrige3InputOutput(){
        return $this->hasMany('App\Models\DetonatorFrige3InputOutput', 'output_id', 'id');
    }

    public function detonator3(){
        return $this->belongsTo('App\Models\DetonatorFrige3', 'det3_id', 'id');
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
