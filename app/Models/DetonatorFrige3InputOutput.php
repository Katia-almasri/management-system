<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetonatorFrige3InputOutput extends Model
{
    use HasFactory;

    protected $table = 'detonator_frige3_input_outputs';
    protected $primaryKey='id';
    protected $fillable = [
       'output_id',
       'input_id',
       'weight',
       'amount'
    ];

    ####################### Begin Relations ###############################
    public function DetonatorFrige3Detail(){
        return $this->belongsTo('App\Models\DetonatorFrige3Detail', 'input_id', 'id');
    }

    public function DetonatorFrige3Output(){
        return $this->belongsTo('App\Models\DetonatorFrige3Output', 'output_id', 'id');
    }

}
