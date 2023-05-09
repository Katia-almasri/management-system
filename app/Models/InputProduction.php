<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InputProduction extends Model
{
    use HasFactory;

    protected $table = 'input_productions';
    protected $primaryKey='id';
    protected $fillable = [
       'type_id',
       'weight',
       'income_date',
       'output_date',
       'weight_detail_id'
    ];



    public function typeChicken(){
        return $this->belongsTo('App\Models\typeChicken', 'type_id', 'id');
    }

    public function inputSlaughter(){
        return $this->belongsTo('App\Models\input_slaughter_table', 'productionId', 'id');
    }

    public function weightAfterArrivalDetectionDetail(){
        return $this->belongsTo('App\Models\weightAfterArrivalDetectionDetail', 'weight_detail_id', 'id');
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
