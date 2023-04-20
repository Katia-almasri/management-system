<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoultryReceiptDetectionsDetails extends Model
{
    use HasFactory;

    protected $table = 'poultry_receipt_detections_details';
    protected $primaryKey='id';
    protected $fillable = [
       'receipt_id',
       'row_material_id',
       'num_cages',
       'tot_weight',
       'num_birds'
    ];

    ######################## Begin Relations ################################
    public function PoultryReceiptDetection(){
        return $this->belongsTo('App\Models\PoultryReceiptDetection', 'receipt_id', 'id');
    }

    public function rowMaterial(){
        return $this->belongsTo('App\Models\RowMaterial', 'row_material_id', 'id');
    }

    public function cageDetails(){
        return $this->hasMany('App\Models\CageDetail', 'details_id', 'id');
    }

    public function weightAfterArrivalDetectionDetail(){
        return $this->hasOne('App\Models\weightAfterArrivalDetectionDetail', 'details_id', 'id');
    }

    ######################## End Relations ################################
}
