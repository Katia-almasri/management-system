<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetonatorFrige2 extends Model
{
    use HasFactory;

    protected $table = 'detonator_frige2s';
    protected $primaryKey='id';
    protected $fillable = [
       'warehouse_id',
       'weight',
       'amount'
    ];

     ############################## Begin Relations #############################
     public function warehouse(){
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_id', 'id');
    }

    public function det2Outputs(){
        return $this->hasMany('App\Models\DetonatorFrige2Output', 'det2_id', 'id');
    }


    public function detonatorFrige2Details(){
        return $this->hasMany('App\Models\DetonatorFrige2Detail', 'detonator_frige_2_id', 'id');
    }



}
