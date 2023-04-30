<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class typeChicken extends Model
{
    use HasFactory;

    protected $table = 'type_chickens';
    protected $primaryKey='id';
    protected $fillable = [
       'type',
       'total_amount',
    ];

    public function inputProduction(){
        return $this->hasOne('App\Models\InputProduction', 'type_id', 'id');
    }
}
