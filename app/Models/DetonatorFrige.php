<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetonatorFrige extends Model
{
    use HasFactory;

    protected $table = 'detonator_friges';
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

    public function detonatorFrigeDetails(){
        return $this->hasMany('App\Models\DetonatorFrigeDetail', 'detonator_frige_id', 'id');
    }

}
