<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remnat extends Model
{
    use HasFactory;
    protected $table = 'remnats';
    protected $primaryKey='id';
    protected $fillable = [
       'type_remant_id',
       'weight'
    ];

     ############################## Begin Relations #############################
     public function type_remnat(){
        return $this->belongsTo('App\Models\RemnantsType', 'type_remant_id', 'id');
    }

    public function remnat_details(){
        return $this->hasMany('App\Models\RemnatDetail', 'remant_id', 'id');
    }
    ############################## End Relations ##############################
}
