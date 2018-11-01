@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
      <li class="breadcrumb-item">
          <a href="{{ url('/clients')}}">לקוחות</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#">{{$client->name}}</a>
          </li>

  </ol>
  <div class="row">
  <div class="col-md-1 ">
        {!! Form::open(['action'=> ['ClientsController@destroy',$client->id], 'method' => 'POST']) !!} 
      @method('DELETE')
      {{ csrf_field() }}
      {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}
  
      {{Form::submit('הסר לקוח', ['class' => 'deleteAlert btn btn-primary'])}}
      {!! Form::close() !!} 
  </div>
 
  <div class="col-md-1 ">
        {!! Form::open(['action'=> ['ClientsController@update',$client->id] ,'method' => 'POST']) !!} 
        {{ method_field('PUT') }}
        {!! csrf_field() !!}
        <div class=" ">{{Form::submit('עדכון', ['class' => 'btn btn-primary'])}} </div>
  
  </div>  
  </div>
  <br>
  <div class="row">
     
          <div class=" col-md-12  form-group">
                  <div class="  form-group text-right"> {!! Form::text('clientName',$client->name,['placeholder' => 'שם','class' => 'text-right form-control'] ) !!}  </div>
                  <div class=" form-group text-right">{!! Form::text('email',$client->email,['placeholder' => 'מייל','class' => 'text-right form-control '] ) !!} </div>
                  <div class=" form-group text-right">{!! Form::text('clientNumber',$client->number,['placeholder' => 'טל','class' => 'text-right form-control '] ) !!} </div>
                  <div class=" form-group text-right">{!! Form::text('clientCity',$client->city,['placeholder' => 'עיר','class' => 'text-right form-control '] ) !!} </div>
                  <div class=" form-group text-right">{!! Form::text('clientAddress',$client->address,['placeholder' => 'כתובת','class' => 'text-right form-control '] ) !!} </div> 
                 
      
          </div>  
          
  
      
     </div>
   
   {!! Form::close() !!} 
   


 
      @endsection
      @section('js')
     
        <script src={{ asset('js/deleteAlert.js') }}></script>
       
       
    
     
      @stop