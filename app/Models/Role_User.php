<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_User extends Model
{
    use HasFactory;
    public $timestamps  = false;

    protected $table = 'role_user';
    protected $primaryKey='role_id, user_id'; //to trat multiple cols as pk
    protected $fillable = [
       'user_type'
    ];

}
