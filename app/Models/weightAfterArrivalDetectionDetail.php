<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class weightAfterArrivalDetectionDetail extends Model
{
    use HasFactory;

    protected $table = 'after_arrival_detection_details';
    protected $primaryKey='id';
    protected $fillable = [
       'detection_id',
       'details_id',
       'dead_chicken',
       'tot_weight_after_arrival',
       'weight_loss',
       'net_weight_after_arrival',
       'current_weight',
       'approved_at'

    ];

    ######################## Begin Relations ##########################
    public function weightAfterArrivalDetection(){
        return $this->belongsTo('App\Models\weightAfterArrivalDetection', 'detection_id', 'id');
    }

    public function PoultryReceiptDetectionsDetails(){
        return $this->belongsTo('App\Models\PoultryReceiptDetectionsDetails', 'details_id', 'id');
    }
    public function inputProductions(){
        return $this->hasMany('App\Models\InputProduction', 'weight_detail_id', 'id');
    }
    ######################### End Relations ############################

}
