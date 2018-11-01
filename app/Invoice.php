<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'debt',
        'paid',
        'sent'
        

    ];
    public function client(){
        return $this->belongsTo('App\Client');
     }
}
