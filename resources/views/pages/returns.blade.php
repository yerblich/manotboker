@extends('layouts.main')
@section('content')


  <style>
.datepicker{
  width: 94%;
padding: 1%;
}
.go{
  padding: 1%;
}
  </style>
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">חזרות</a>
    </li>

  </ol>


  <div class="row">
      <div class=" col-md-12 ">
        {!! Form::open(['action'=> 'ReturnsController@create', 'method' => 'POST']) !!}
        {{ csrf_field() }}
        {{Form::text('date',null,   ['class' => 'datepicker ', 'autocomplete' => 'off'])}}


        {{ Form::button('<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>', ['class' => 'btn btn-primary btn-lg ', 'type' => 'submit']) }}

        {!! Form::close() !!}
          </div>




</div>

{{--

    @if(count($returns) > 0 )
    @foreach($returns as $date => $object )
    <div  class="row col-12 border text-primary centerTable dateGroup">

     <div class="row col-12"><h5>{{$date}}</h5></div>



    </div>
     <div style="display:none" class=" row well centerTable orderList">
    @foreach($object as $return)

    <div class="dateGroupItem">

        <div class="dateGroup"><a  href="{{url('/returns')}}/{{$return->date}}"> {{$return->date->format('d') }}</a></div>


     </div>


    @endforeach
  </div>

  @endforeach

  @else
    <p class="text-right"> לא נמצאו חזרות </p>
  @endif --}}






      @endsection
      @section('js')



    @stop
