<?php

namespace App;
use Schema;
use Illuminate\Database\Eloquent\Model;


class Price extends Model
{
  
    public function __construct() {

        $this->fillable(\Schema::getColumnListing($this->getTable()));

    }
    public function client(){
        return $this->belongsTo('App\Client');
     }
}
