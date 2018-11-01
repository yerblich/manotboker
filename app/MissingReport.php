<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MissingReport extends Model
{
    protected $fillable = [
        'supplier_id',
        'from_date',
        'to_date'
        
        

    ];
    protected $dates = ['from_date','to_date'];
    
    
    public function supplier(){
        return $this->belongsTo('App\Supplier');
     }


}

