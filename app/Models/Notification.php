<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey='id';
    protected $fillable = [
       'channel',
       'event',
       'title',
       'route',
       'act_id',
       'details',
       'is_seen',
    ];

    #################### Accessors & mutators #########################
    public  function getAllNotifications()
    {
        return Notification::where([['channel', '=', 'add-start-command-notification'],
        ['is_seen', '=', 0]
         ])->get();
    }
}
