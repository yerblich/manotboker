@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <style>
  .table
    {

  margin-left:15em;
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
          <a href="{{ url('/suppliers')}}">ספקים</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ url('/suppliers')}}/{{$supplier->id}}">{{$supplier->name}}</a>
          </li>
    <li class="breadcrumb-item">
      <a href="#">דוחות</a>
    </li>
  </ol>












    @if(count($allReports) > 0 )

    @foreach($allReports as $month => $reports )
    <div  class="row col-12 border text-primary centerTable dateGroup">

     <div class="row col-12"><h5>{{$month}}</h5></div>



    </div>
     <div style="display:none" class="row  well centerTable orderList">
    @foreach($reports as $report)


  <div class="dateGroupItem ">

  <div class=" dateGroup "><a  href="{{ url('/reports')}}/{{$report->id}}"> {{$report->from_date->format('d-m-Y')}} To {{$report->to_date->format('d-m-Y')}}</a></div>


 </div>

    @endforeach
  </div>

  @endforeach

  @else
    <p> לא נמצאו דוחות </p>
  @endif


      @endsection
