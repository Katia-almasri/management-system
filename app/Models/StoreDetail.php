<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreDetail extends Model
{
    use HasFactory;

    protected $table = 'store_details';
    protected $primaryKey='id';
    protected $fillable = [
       'store_id',
       'weight',
       'amount',
       'cur_weight',
       'cur_amount',
       'date_of_destruction',
       'expiration_date',
       'output_slaughter_detail_id',
       'input_from'
    ];

    ############################## Begin Relations #############################
    public function store(){
        return $this->belongsTo('App\Models\Store', 'store_id', 'id');
    }

    public function storeInputsOutputs(){
        return $this->hasMany('App\Models\StoreInputOutput', 'input_id', 'id');
    }

    //MORPH RELATIONSHIP BTN DETAILS AND(SLAUGHTER, .., .., SAWA3E8)
    //الدخل إلى تفاصيل البحرات
    public function inputable(){
        return $this->morphTo();
    }

    public function detonatorFrige1Output(){
        return $this->morphOne('App\Models\DetonatorFrige1Output', 'outputable');
    }

    public function detonatorFrige2Output(){
        return $this->morphOne('App\Models\DetonatorFrige2Output', 'outputable');
    }

    public function detonatorFrige3Output(){
        return $this->morphOne('App\Models\DetonatorFrige3Output', 'outputable');
    }

}
