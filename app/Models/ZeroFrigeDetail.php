<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZeroFrigeDetail extends Model
{
    use HasFactory;

    protected $table = 'zero_frige_details';
    protected $primaryKey='id';
    protected $fillable = [
       'zero_frige_id',
       'weight',
       'amount',
       'cur_weight',
       'cur_amount',
       'date_of_destruction',
       'expiration_date',
       'input_from'
    ];

    ############################## Begin Relations #############################
    public function zeroFrige(){
        return $this->belongsTo('App\Models\ZeroFrige', 'zero_frige_id', 'id');
    }

    public function ZeroFrigeInputOutput(){
        return $this->hasMany('App\Models\ZeroFrigeInputOutput', 'input_id', 'id');
    }

    //MORPH RELATIONSHIP BTN DETAILS AND(SLAUGHTER, .., .., SAWA3E8)
       //الدخل إلى تفاصيل البراد الصفري
    public function inputable(){
        return $this->morphTo();
    }

    public function inputToZeroDetail()
    {
        return $this->morphOne('App\Models\LakeOutput', 'outputable');
    }

    public function LakeOutput()
    {
        return $this->morphOne('App\Models\LakeOutput', 'outputable');
    }
    //outputable method from(output cutting detail)
    public function output_cutting_detail()
    {
        return $this->morphOne('App\Models\output_cutting_detail', 'outputable');
    }
    //outputable method from (output manufactoring detail)

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
