<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    use HasFactory;

    protected $table = 'governorates';
    protected $primaryKey='id';
    protected $fillable = [
       'name',
       'distance'
    ];

    public function farms(){
        return $this->hasMany('App\Models\Farm', 'governorate_id', 'id');
    }
}
