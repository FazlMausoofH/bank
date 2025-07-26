<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    //
    protected $fillable = ['date','amount','account_holder','type'];    
}
