<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clocker extends Model
{
  protected $fillable = [
      'start',
      'end',
      'employee',
      'date'


  ];
  protected $dates = ['start', 'end', 'date'];

}
