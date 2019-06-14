<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{
    //
    use SoftDeletes; 
    protected $table ='subscribers';

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'softDelete'
    ];
    protected $dates = ['deleted_at'];
    // protected $softDelete = true;
}
