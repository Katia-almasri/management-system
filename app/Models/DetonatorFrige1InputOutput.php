<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetonatorFrige1InputOutput extends Model
{
    use HasFactory;

    protected $table = 'detonator_frige1_input_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'output_id',
       'input_id',
       'weight',
       'amount'
    ];

    ####################### Begin Relations ###############################
    public function DetonatorFrige1Detail(){
        return $this->belongsTo('App\Models\DetonatorFrige1Detail', 'input_id', 'id');
    }

    public function DetonatorFrige1Output(){
        return $this->belongsTo('App\Models\DetonatorFrige1Output', 'output_id', 'id');
    }


}
