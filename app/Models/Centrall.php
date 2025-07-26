<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Centrall extends Model
{
    //
    protected $fillable = ['faktur','date','amount','account_holder','type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
