<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RowMaterial extends Model
{
    use HasFactory;

    protected $table = 'row_materials';
    protected $primaryKey='id';
    protected $fillable = [
       'name'
    ];

    ####################### Begin Relations #########################
    public function PoultryReceiptDetectionsDetails(){
        return $this->belongsTo('App\Models\PoultryReceiptDetectionsDetails', 'row_material_id', 'id');
    }

    ####################### End Relations #########################

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
