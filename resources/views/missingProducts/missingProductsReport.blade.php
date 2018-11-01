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

    <a href="{{ url('/suppliers')}}/{{$data['supplier']->id}}">{{$data['supplier']->name}}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="#">דוח מוצרים חסר</a>
      </li>
  </ol>
  <div class="float-right ">
      {!! Form::open(['action'=>[ 'ReportsController@store'],'method' => 'POST']) !!}
      {{ csrf_field() }}
      {!! Form::hidden('supplier_id',$data['supplier']->id) !!}
      {!! Form::hidden('from_date',$data['from_date'] ) !!}
      {!! Form::hidden('to_date',$data['to_date'] ) !!}

      {{Form::submit('Save',  ['name' => 'save','class' => 'btn btn-primary'])}}
      {{Form::submit('שלח Pdf',  ['name' => 'send','class' => 'btn btn-primary'])}}
      {!! Form::close() !!}
      </div>
      <br>
  <div class="table-responsive">
      <table class="table-bordered table table-striped">

                  <thead>

                      <tr>
                        <th>תאריך</th>
        @foreach ($data['names'] as  $name => $id)
                      <th>{{$name}}</th>
                      <th class= "returnsBg">ח</th>

        @endforeach
      </tr>
      </thead>
      <tbody>

              @foreach ($data['orders'] as $date => $array)
              <tr>
              <th style="white-space:nowrap">{{$date}}</th>
              @foreach ($array as $id => $quantity)
              <td>{{$quantity}}</td>
              @if(array_key_exists($date,$data['missingProducts']))
              <td class= "returnsBg">{{$data['missingProducts'][$date][$id]}}</td>
              @else
              <td class= "returnsBg">0</td>
              @endif
              @endforeach
            </tr>
            @endforeach



      </tbody>
      <tfoot>
        <tr>
        <th>סה"כ</th>
       @foreach ($data['orderSums'] as $id => $sum)
           <th class="">{{$sum}} </th>
        <td class= "returnsBg">{{$data['missingSums'][$id]}}</td>
       @endforeach


        </tr>
        <tr>
            <th>   סה"כ תשלום</th>
           @foreach ($data['totaloProductsCosts'] as $id => $cost)
               <th class="">&#8362;{{$cost}} </th>
            <td class= "returnsBg">&#8362;{{$data['totalmProductsCosts'][$id]}}</td>
           @endforeach


            </tr>

      </tfoot>

      </table>
    </div>
    <div class="row">
    <div class=" table-responsive col-md-12 text-center">
      <table class="table   table-bordered">
        <thead>
          <th>סך עלות ההזמנות </th>
          <th>סך עלות חסר </th>
          <th>חוב סופי</th>
        </thead>
        <tbody>
        <td>&#8362;{{array_sum($data['totaloProductsCosts'])}}</td>
        <td>&#8362;{{array_sum($data['totalmProductsCosts'])}}</td>
        <td class="returnsBg">&#8362;{{array_sum($data['totaloProductsCosts']) - array_sum($data['totalmProductsCosts'])}}</td>
        </tbody>
        <tfoot>

        </tfoot>
      </table>

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
