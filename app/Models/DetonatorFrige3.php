<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetonatorFrige3 extends Model
{
    use HasFactory;

    protected $table = 'detonator_frige3s';
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

    public function det3Outputs(){
        return $this->hasMany('App\Models\DetonatorFrige3Output', 'det3_id', 'id');
    }


    public function detonatorFrige3Details(){
        return $this->hasMany('App\Models\DetonatorFrige3Detail', 'detonator_frige_3_id', 'id');
    }



}
