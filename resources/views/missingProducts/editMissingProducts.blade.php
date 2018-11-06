@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <style>

    .headcol {
      text-align: center;
      height: 49px;
    position: absolute;
    width: 15.3em;
    margin-left: -219px;
    background-color: white;
    z-index: 1;
    }
  </style>
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Missing Products</a>
    </li>

  </ol>
  <div class="row">
      <div class="col-md-10">
<h1>תאריך : {{$data['date']}}</h1>
      </div>
      <div class="col-md-2">
            {!! Form::open(['action'=> ['MissingProductsController@destroy',$data['id']], 'method' => 'POST'],['class' => 'form-group']) !!}
            @method('Delete')

          {{Form::submit('הסר דו"ח', ['class'=> 'deleteAlert btn btn-primary','id'=>"progress"])}}
      {!! Form::close() !!}
      </div>
  </div>
  {!! Form::open(['action'=> ['MissingProductsController@update',$data['id']], 'method' => 'POST'],['class' => 'form-group']) !!}
  @method('PUT')
  <div class="table-responsive">
        <table class="table-bordered table">

                    <thead>
                            <tr>
          @foreach ($data['missingProducts'] as $name => $quantity)
            <th>{{$name}}</th>

          @endforeach
        </tr>
        </thead>
        <tbody>
        <tr>
                @foreach ($data['missingProducts'] as $name => $quantity)
                <td>{{Form::input('number',$data['missingProductsIds'][$name],$quantity,['onkeypress'=> "return isNumberKey(event)", 'class' => 'form-control','style' => 'padding:1px; text-align:center'] )}}</td>

              @endforeach

        </tr>

        </tbody>


        </table>
      </div>

      {{Form::submit('עדכון', ['class'=> 'btn btn-primary','id'=>"progress"])}}
      {!! Form::close() !!}





      @endsection
      @section('js')

        <script src={{ asset('storage/js/isNumberKey.js') }}></script>

        <script src={{ asset('storage/js/deleteAlert.js') }}></script>


      @stop
