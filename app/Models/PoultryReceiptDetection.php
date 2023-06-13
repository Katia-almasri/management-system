<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoultryReceiptDetection extends Model
{
    use HasFactory;

    protected $table = 'poultry_receipt_detections';
    protected $primaryKey='id';
    protected $fillable = [
       'farm_id',
       'libra_commander_id',
       'is_weighted_after_arrive',
       'tot_weight',
       'empty',
       'net_weight',
       'num_cages',
       'is_seen_by_sales_manager',
       'trip_id'


    ];

    ############################ Begin Relations ##############################
    public function farm(){
        return $this->belongsTo('App\Models\Farm', 'farm_id', 'id');
    }

    public function libraCommander(){
        return $this->belongsTo('App\Models\Manager', 'libra_commander_id', 'id');
    }

    public function PoultryReceiptDetectionDetails(){
        return $this->hasMany('App\Models\PoultryReceiptDetectionsDetails', 'receipt_id', 'id');
    }

    public function weightAfterArrivalDetection(){
        return $this->hasOne('App\Models\weightAfterArrivalDetection', 'polutry_detection_id', 'id');
    }

    public function trip(){
        return $this->belongsTo('App\Models\Trip', 'trip_id', 'id');
    }

    ############################ End Relations ##############################

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
