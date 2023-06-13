<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputManufacturing extends Model
{
    use HasFactory;

    protected $table = 'input_manufacturings';
    protected $primaryKey='id';
    protected $fillable = [
       'weight',
       'income_date',
       'output_date',
       'output_manufacturing_id',
       'type_id',
       'input_from'
    ];

    //morph inputable
    public function inputable(){
        return $this->morphTo();
    }
    //outputable method from (output cutting detail)
    public function output_cutting_detail()
    {
        return $this->morphOne('App\Models\output_cutting_detail', 'outputable');
    }

    public function ZeroFrigeOutput()
    {
        return $this->morphOne('App\Models\ZeroFrigeOutput', 'outputable');
    }




    public function output_manuFacturing(){
        return $this->BelongsTo('App\Models\OutputManufacturing', 'output_manufacturing_id', 'id');
    }

    public function output_types(){
        return $this->belongsTo('App\Models\outPut_Type_Production', 'type_id', 'id');
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
