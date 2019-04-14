<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    protected $fillable = [
        'debt',
        'credit',
        'name',
        'route'

    ];

 public function orders(){

    return $this->hasMany('App\Order');
 }

 public function returns(){

    return $this->hasMany('App\ProductReturn');
 }
 public function PrevReturns(){

    return $this->hasMany('App\PrevReturn');
 }

 public function prices(){
    return $this->hasMany ('App\Price');
 }
 public function invoices(){

    return $this->hasMany('App\Invoice');
 }

 public function credits(){

    return $this->hasMany('App\Credit');
 }
}
