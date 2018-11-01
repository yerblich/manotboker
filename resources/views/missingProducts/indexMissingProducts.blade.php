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
      <a href="#">מוצרים חסרים</a>
    </li>
  </ol>






  {!! Form::open(['action'=> 'MissingProductsController@create', 'method' => 'POST','class' => 'form-group']) !!}
 <div class="row form-group">
    {{Form::hidden('supplierId', $supplier->id)}}
 <div class = "col-12">{{Form::text('date', null, array('placeholder'=>'תאריך','class' => 'text-right form-control datepicker', 'autocomplete' => 'off', 'required' => 'required'))}} </div>

</div><br/>

{{-- create table for each type of product i.e shabbos and daily  --}}


{{Form::submit('בוחר תאריך', ['class'=> ' form-control btn btn-primary'])}}
{!! Form::close() !!}






    @if(count($datesWithMissing) > 0 )

    @foreach($datesWithMissing as $month => $missingProducts )
    <div  class="row col-12 border text-primary centerTable dateGroup">

     <div class="row col-12"><h5>{{$month}}</h5></div>



    </div>
     <div style="display:none" class="row  well centerTable orderList">
    @foreach($missingProducts as $missingProduct)


  <div class="dateGroupItem ">

    <div class=" dateGroup "><a  href="{{ url('/missingProducts')}}/{{$missingProduct->id}}"> {{$missingProduct->date->format('d')}}</a></div>


 </div>

    @endforeach
  </div>

  @endforeach

  @else
    <p>לא נמצאו מוצרום חסרים </p>
  @endif


      @endsection
