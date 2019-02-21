<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{   // allow us to pass all fields to the constructor
    protected $fillable = [
        'time',
        'title',
        'description'
    ];
    // create relationship with meeting model
    public function users(){
        return $this->belongsToMany('App\User');
    }
}
