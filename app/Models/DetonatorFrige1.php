<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetonatorFrige1 extends Model
{
    use HasFactory;

    protected $table = 'detonator_frige1s';
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

    public function detonatorFrige1Details(){
        return $this->hasMany('App\Models\DetonatorFrige1Detail', 'detonator_frige_1_id', 'id');
    }

    public function det1Outputs(){
        return $this->hasMany('App\Models\DetonatorFrige1Output', 'det1_id', 'id');
    }




}
