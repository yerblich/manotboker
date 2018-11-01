@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">חזרות</a>
    </li>

  </ol>
  <div class="row">
      <div class=" col-md-9 ">
          {{-- <a class="" href="{{ url('returns/create')}}">
            <button type="button" class="btn btn-primary">Create Return</button>
          </a> --}}
          </div>
  <div class="col-md-3 " >
      {{-- {!! Form::open(['action'=> 'ordersController@pdfSend', 'method' => 'POST']) !!}
      {{ csrf_field() }}

      {{Form::submit('חפש',  ['class' => 'datepicker btn btn-primary'])}}
      {!! Form::text('date',null,['class' => 'datepicker ', 'autocomplete' => 'off'] ) !!}

      {!! Form::close() !!} --}}
    </div>

</div>



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
  @endif






      @endsection
