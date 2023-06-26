<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetonatorFrige3Detail extends Model
{
    use HasFactory;

    protected $table = 'detonator_frige3_details';
    protected $primaryKey='id';
    protected $fillable = [
       'detonator_frige_3_id',
       'weight',
       'amount',
       'cur_weight',
       'cur_amount',
       'date_of_destruction',
       'expiration_date',
       'input_from',
       'cur_output_weight' 
    ];

    ############################## Begin Relations #############################
    public function detonatorFrige3(){
        return $this->belongsTo('App\Models\DetonatorFrige3', 'detonator_frige_3_id', 'id');
    }

    public function DetonatorFrige3InputOutput(){
        return $this->hasMany('App\Models\DetonatorFrige3InputOutput', 'input_id', 'id');
    }

    //MORPH RELATIONSHIP BTN DETAILS AND(SLAUGHTER, .., .., SAWA3E8)
    //الدخل إلى تفاصيل الصاعقة 3
    public function inputable(){
        return $this->morphTo();
    }

    public function ZeroFrigeOutput()
    {
        return $this->morphOne('App\Models\ZeroFrigeOutput', 'outputable');
    }

    public function LakeOutput()
    {
        return $this->morphOne('App\Models\LakeOutput', 'outputable');
    }

    //outputable method from (output manufactoring detail)

    public function OutputManufacturingDetails()
    {
        return $this->morphOne('App\Models\OutputManufacturingDetails', 'outputable');
    }

     ############################# Begin Accessors ##############################endregion
     public function getCreatedAtAttribute($date)
     {
         if($date!=null)
             return Carbon::parse($date)->format('Y-m-d H:i');
         return $date;
     }
 
     public function getUpdatedAtAttribute($date)
     {
         if($date!=null)
             return Carbon::parse($date)->format('Y-m-d H:i');
         return $date;
     }

}
