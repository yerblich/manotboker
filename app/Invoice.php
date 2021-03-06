<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'debt',
        'paid',
        'sent',
        'printed',
        'invoice_num'


    ];
    protected $dates = ['from_date', 'to_date'];

    public function client(){
        return $this->belongsTo('App\Client');
     }
}
