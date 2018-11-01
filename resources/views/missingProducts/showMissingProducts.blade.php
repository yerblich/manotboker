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

          <a href="{{ url('/suppliers')}}/{{$data['supplier']->id}}">{{$data['supplier']->name}}</a>
          </li>
    <li class="breadcrumb-item">
      <a href="#"> מוצרים חסרים</a>
    </li>

  </ol>
  <div class="row">
      <div class="col-md-11">
<h1>תאריך : {{$data['date']}}</h1>
      </div>
      <div class="col-md-1">
          <a href="{{ url('/missingProducts')}}/{{$data['id']}}/edit" class="btn btn-primary">edit </a>
      </div>
  </div>
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
                <th>{{$quantity}}</th>

              @endforeach

        </tr>

        </tbody>


        </table>
      </div>







      @endsection
