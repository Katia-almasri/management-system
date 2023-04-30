<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetonatorFrigeOutput extends Model
{
    use HasFactory;

    protected $table = 'detonator_frige_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'detonator_frige_details_id',
       'output_date',
       'note'
    ];

     ############################## Begin Relations #############################
     public function detonatorFrigeDetail(){
        return $this->belongsTo('App\Models\DetonatorFrigeDetail', 'detonator_frige_details_id', 'id');
    }
}
