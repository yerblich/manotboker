@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <style>
  .returnsBg{
    background-color: #dc354547;
  }
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

    <a href="{{ url('/suppliers')}}/{{$supplier->id}}">{{$supplier->name}}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="#">דוח {{$from_date. "~~" . $to_date}}
        </a>
      </li>
  </ol>

<div class = "row">
        <div class="col-md-2">
                {!! Form::open(['action'=> ['ReportsController@destroy',$report->id], 'method' => 'POST'],['class' => 'form-group']) !!}
                @method('Delete')

              {{Form::submit('הסר דו"ח', ['class'=> 'deleteAlert btn btn-primary','id'=>"progress"])}}
          {!! Form::close() !!}
          </div>
</div>


    <div class="row">

        <div class="col-12">
      <object  data="{{asset("storage/missingReportsPdf/mReport".$from_date. "~~" . $to_date .".pdf")}}" type="application/pdf" width="100%" height="500">
        <iframe src="{{asset("storage/missingReportsPdf/mReport".$from_date. "~~" . $to_date .".pdf")}}" width="100%" height="600"></iframe>
      </object>
      </div>
      </div>



      @endsection
