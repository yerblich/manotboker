@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#"></a>
    </li>

  </ol>
  <div class="row">
  <div class="col-md-10">{!! Form::open(['action'=> ['SuppliersController@destroy',$data['supplier']->id ], 'method' => 'POST']) !!}
      @method('DELETE')
      {{ csrf_field() }}
      {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}

      {{Form::submit('הסר ספק', ['class' => ' deleteAlert btn btn-primary'])}}
      {!! Form::close() !!} </div>

  {!! Form::open(['action'=> ['SuppliersController@update',$data['supplier']->id] ,'method' => 'POST']) !!}
  {{ method_field('PUT') }}
  {!! csrf_field() !!}



  </div>

  <br>
  <div class="row">

          <div class=" col-md-12  form-group">
                  <div class="  form-group text-right"> {!! Form::text('supplierName',$data['supplier']->name,['placeholder' => 'שם','class' => 'text-right form-control'] ) !!}  </div>
                  <div class=" form-group text-right">{!! Form::text('supplierEmail',$data['supplier']->email,['placeholder' => 'מייל','class' => 'text-right form-control '] ) !!} </div>
                  <div class=" form-group text-right">{!! Form::text('supplierNumber',$data['supplier']->number,['placeholder' => 'טל','class' => 'text-right form-control '] ) !!} </div>
                  <div class=" form-group text-right">{!! Form::text('supplierCity',$data['supplier']->city,['placeholder' => 'עיר','class' => 'text-right form-control '] ) !!} </div>
                  <div class=" form-group text-right">{!! Form::text('supplierAddress',$data['supplier']->address,['placeholder' => 'כתובת','class' => 'text-right form-control '] ) !!} </div>


          </div>



     </div>
     <div class="row">
<div class="  col-md-1">{{Form::submit('עדכון', ['class' => 'btn btn-primary'])}} </div>
</div>
   {!! Form::close() !!}




      @endsection
      @section('js')

      <script src={{ asset('js/deleteAlert.js') }}></script>




    @stop
