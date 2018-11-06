@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{url('/orders')}}">הזמנות</a>
    </li>
    <li class="breadcrumb-item">
        <a href="#">{{$data['date']}} - הזמנה</a>
      </li>

  </ol>
  <div class="row">
    <div class="col-md-1 float-left">
      {!! Form::open(['action'=> ['ordersController@destroy',$data['date']], 'method' => 'POST']) !!}
    @method('DELETE')
    {{ csrf_field() }}
    {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}

    {{Form::submit('הסר', ['class' => 'deleteAlert btn btn-primary'])}}
    {!! Form::close() !!}
</div>
  <div class="col-md-8   text-center" >
      <a class="btn btn-primary" href="{{ url('/orders')}}/{{$data['date'] }}/edit">עדכון</a>
      {{-- {!! Form::open(['action'=> ['ordersController@edit',$data['date'] ], 'method' => 'GET']) !!}
      {{ csrf_field() }}
      <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a>
      {!! Form::hidden('pdf', json_encode($data,TRUE)) !!}
      {{Form::submit('Edit', ['class' => 'btn btn-primary'])}}
      {!! Form::close() !!} --}}
    </div>






  <div class="col-md-3 dropdown">
    {!! Form::open(['action'=> 'ordersController@pdfSend', 'method' => 'POST']) !!}
    {{ csrf_field() }}
    {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}
    {!! Form::hidden('pdf', json_encode($data,TRUE)) !!}
    <button class="btn btn-primary dropdown-toggle"  type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    שלח הזמנה
    </button>
    <div class="dropdown-menu ddpad" aria-labelledby="dropdownMenuButton">
      @foreach($data['suppliers'] as $supplier)
      {{ Form::checkbox($supplier->id, $supplier->name,['class' => 'menu-item'])}}
      {{ Form::label($supplier->id, $supplier->name, ['class' => 'menu-item']) }}<br>

      @endforeach

      <hr>
      {{Form::submit('שלח Pdf',  ['class' => ' w-100  progressMI btn btn-primary'])}}
    </div>

  {!! Form::close() !!}
</div>


</div>
<hr>

<div class="border 1px col-md-2" >תאריך: {{ $data['date'] }}</div>
<br>


{{-- shabbos table --}}
<div class="row">

  <div class="col-12">
<object  data="{{asset("storage/pdf/order". $data['date'].".pdf")}}" type="application/pdf" width="100%" height="500">
  <iframe src="{{asset("storage/pdf/order". $data['date'].".pdf")}}" width="100%" height="600"></iframe>
</object>
</div>
</div>

<div id="modalbar" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">טעינה</h5>

      </div>
      <div class="modal-body">

        <div style="display:none" id="progressbar"></div>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
      @endsection
      @section('js')

      <script src={{ asset('storage/js/deleteAlert.js') }}></script>




    @stop
