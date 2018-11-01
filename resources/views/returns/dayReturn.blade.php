@extends('layouts.main')
@section('content')
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
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
    <a href="{{url('/returns')}}">חזרות</a>
    </li>
    <li class="breadcrumb-item">
        <a href="#">{{ $data['date'] }}</a>
        </li>

  </ol>
  <div class="row">


</div>
<hr>
<div class="col-md-3 float-right" >
    {!! Form::open(['action'=> 'ordersController@pdfSend', 'method' => 'POST']) !!}
    {{ csrf_field() }}
    {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}
    {!! Form::text('date',null,['class' => 'datepicker ', 'autocomplete' => 'off'] ) !!}
    {{Form::submit('חפש',  ['class' => 'datepicker btn btn-primary'])}}
    {!! Form::close() !!}
  </div>

<div class="border 1px col-md-2" >תאריך: {{ $data['date'] }}</div>
<br>
<div class="row">
{!! Form::open(['action'=> 'ReturnsController@store', 'method' => 'POST'],['class' => 'form-group']) !!}
{{Form::hidden('date', $data['date'] , array('class' => 'datepicker', 'autocomplete' => 'off', 'required' => 'required'))}}</div><br/>
@foreach($data['products'] as $orderType => $products)
@if(count($data['products'][$orderType]))
<h1>{{ ucfirst($orderType)}}</h1>
<div class="table-responsive">
  <table class="table table-bordered table-striped table-responsive" id="dataTable" width="100%" cellspacing="0">
    <thead class="thead-light">
      <tr>
          <th class="headcol">לקוחות</th>
  @if(count($products) > 0 )

    @foreach($products as $product)


      <th>{{$product->name}}</th>


    @endforeach

  @else
    <p> No Products </p>
  @endif
</tr>
</thead>

<tbody>
  {{-- in middle of tryiing to only display rows with filled ordders --}}
    @foreach($data['clientsWithOrders'] as $clientOrder)

    <tr>

        <td class="headcol">{{$clientOrder->name}}</td>
        @foreach($clientOrder->products[$orderType] as $product_id => $quantity)

        <td>{{Form::input('number',$clientOrder->id. "_" . $product_id,$quantity,['onkeypress'=> "return isNumberKey(event)",'class' => 'form-control col-12' ,'style' => 'padding:1px; text-align:center'])}}</td>


         @endforeach

      </tr>

  @endforeach


</tbody>
<tfoot>
  <tr>
      <th class="headcol">לקוחות</th>
      @foreach($products as $product)


      <th>{{$product->name}}</th>


    @endforeach

  </tr>
</tfoot>
</table>
</div>

@endif
@endforeach
{{Form::submit('submit')}}
{!! Form::close() !!}
</div>
      @endsection
      @section('js')
    
        <script src={{ asset('js/isNumberKey.js') }}></script>




      @stop
