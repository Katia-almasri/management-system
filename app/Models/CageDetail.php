<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CageDetail extends Model
{
    use HasFactory;


    protected $table = 'cage_details';
    protected $primaryKey='id';
    protected $fillable = [
       'details_id',
       'cage_weight',
       'num_birds'
    ];

    ################### Begin Relations #######################
    public function PoultryReceiptDetectionsDetail(){
        return $this->belongsTo('App\Models\PoultryReceiptDetectionsDetails', 'details_id', 'id');
    }
    ################### End   Relations #######################
}
