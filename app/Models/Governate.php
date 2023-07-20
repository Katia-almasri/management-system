<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governate extends Model
{
    use HasFactory;
    protected $table = 'governorates';
    protected $primaryKey='id';
    protected $fillable = [
       'name',
       'distance',
    ];

    ################## Begin Relations #########################endregion

    public function farms(){
        return $this->hasMany('App\Models\Farm', 'governorate_id', 'id');
    }

    public function sellingPorts(){
        return $this->hasMany('App\Models\SellingPort', 'governorate_id', 'id');
    }
}
