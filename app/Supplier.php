<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{

  

    public function products(){

        return $this->hasMany('App\Product');
     }
     public function missingProducts(){

        return $this->hasMany('App\MissingProduct');
     }

     public function missingReports(){

        return $this->hasMany('App\MissingReport');
     }
}
