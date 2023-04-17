<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class weightAfterArrivalDetection extends Model
{
    use HasFactory;

    protected $table = 'weight_after_arrival_detections';
    protected $primaryKey='id';
    protected $fillable = [
       'libra_commander_id',
       'polutry_detection_id',
       'dead_chicken',
       'tot_weight_after_arrival',
       'weight_loss',
       'net_weight_after_arrival'
       
    ];

    ########################## Begin Relations ##############################
    public function libraCommander(){
        return $this->belongsTo('App\Models\Manager', 'libra_commander_id', 'id');
    }
    public function poltryDetection(){
        return $this->belongsTo('App\Models\PoultryReceiptDetection', 'polutry_detection_id', 'id');
    }

    public function weightAfterArrivalDetectionDetail(){
        return $this->hasMany('App\Models\weightAfterArrivalDetectionDetail', 'detection_id', 'id');
    }

    ########################## End Relations #################################

}
