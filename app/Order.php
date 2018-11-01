<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'client_id'
        
        

    ];



    protected $dates = ['date'];

    public function client(){
       return $this->belongsTo('App\Client');
    }
    public function return(){
        return $this->hasOne('App\ProductReturn');
     }
     
     public function orderItems(){
        return $this->hasMany('App\orderItem');
     }
}
