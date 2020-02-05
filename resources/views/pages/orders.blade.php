@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">הזמנות</a>
    </li>

  </ol>
  <div class="row">
  <div class="col-md-9" >
      {{-- {!! Form::open(['action'=> 'ordersController@pdfSend', 'method' => 'POST']) !!}
      {{ csrf_field() }}

      {{Form::submit('חפש',  ['class' => 'datepicker btn btn-primary'])}}
      {!! Form::text('search',null,['class' => 'datepicker ', 'autocomplete' => 'off'] ) !!}

      {!! Form::close() !!} --}}
    </div>
  <div class=" col-md-3 float-rigth">
  <a class="" href="{{ url('orders/create')}}">
    <button type="button" class="btn btn-primary">צור הזמנה</button>
  </a>
  </div>
</div>




    @if(count($orders) > 0 )

    @foreach($orders as $date => $object )
    <div  class="row col-12 border text-primary centerTable dateGroup">

     <div class="row col-12"><h5>{{$date}}</h5></div>



    </div>
     <div style="display:none" class="row  well centerTable orderList">
    @foreach($object as $order)


  <div class="dateGroupItem ">

    <div class=" dateGroup "><a  href='{{url("/orders/$order->date")}}'> {{$order->date->format('d') }}</a></div>


 </div>

    @endforeach
  </div>

  @endforeach

  @else
    <p> לא נמצאו הזמנות </p>
  @endif






{{-- <div class="row">
<div class="paginateLinks"> {{ $clients->links() }}</div>
</div> --}}







      @endsection
