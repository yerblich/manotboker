@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <style>
  .table
    {
      overflow: initial;
  /* margin-left:15em; */
    }

    thead th {
      position: sticky; top: 0; z-index: 100;
    }

  </style>
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">ערוך הזמנה</a>
    </li>

  </ol>




  <hr>


  {!! Form::open(['action'=> ['ordersController@update',$data['date']], 'method' => 'POST'],['class' => 'form-group']) !!}
  @method('PUT')

  <div class="row form-group">
    <div class="col-4">{{Form::text('day', $data['day'], ['placeholder'=>'יום', 'class'=> 'text-right form-control','required' => 'required'])}}   </div>
<div class="col-4">  {{Form::text('parsha', $data['parsha'],['placeholder'=>'פרשת','class'=> 'text-right form-control','required' => 'required'])}}  </div>

 <div class = "col-4">{{Form::text('date', $data['date'], array('placeholder'=>'תאריך','class' => 'text-right form-control', 'autocomplete' => 'off', 'required' => 'required','readonly'))}} </div>
</div><br/>
{{-- create table for each type of product i.e shabbos and daily  --}}
 @foreach($data['products'] as $orderType => $products)
<h1>{{ ucfirst($orderType)}}</h1>
 <div class=" tableWrapper blueScroll table-responsive form-group">
  <table class=" sticky table table-striped table-bordered table-responsive" id="dataTable" width="100%" cellspacing="0">
    <thead class="thead-light">
      <tr>
          <th class="headcol">לקוחות</th>

          {{-- itirate through alll products and create header  --}}
  @if(count( $products) > 0 )

    @foreach($products as $product)


        <th>{{$product->name}}</th>

    @endforeach

  @else
    <p> No Products </p>
  @endif
</tr>
</thead>
<tfoot>
    <tr>
        <td class="headcol">סה"כ</td>
        {{-- table footer --}}
        @foreach($products  as $product)


        <td >{{Form::text('sum_'.$product->id,'',['class' => 'sum_'.$product->id.'  form-control col-12' ,'style' => 'padding:1px; text-align:center','readonly'])}} </td>


      @endforeach
  <tr>
  <tr>
      <th class="headcol">לקוחות</th>
      {{-- table footer --}}
      @foreach($products  as $product)


      <td>{{$product->name}}</td>


    @endforeach
  </tr>
</tfoot>
<tbody>

  @foreach($data['clientList'] as $clientName => $client)

   <tr>
      <td style="min-height:52px;" class="headcol">{{$clientName}}</td>

      @foreach($products  as $product)
      @if(isset($client[$product->id]))
      <td>{{Form::input('number',$data['clientIds'][$clientName]. "_" . $product->id, $client[$product->id],['onkeypress'=> "return isNumberKey(event)",'class' => 'amount_'.$product->id.' test form-control col-12' ,'style' => 'padding:1px; text-align:center'])}} </td>
     @else
     <td>{{Form::input('number',$data['clientIds'][$clientName]. "_" . $product->id,0,['onkeypress'=> "return isNumberKey(event)",'class' => 'amount_'.$product->id.' test  form-control col-12' ,'style' => 'padding:1px; text-align:center'])}} </td>
     @endif
     @endforeach





    </tr>




@endforeach

  {{-- iterate through orders  --}}
    {{-- @foreach($data['orders'] as $order) --}}
    {{-- <tr>
        <td>{{$order->client->name}}</td> --}}
        {{-- iterate through products table in order to find the value corresponding to that product --}}
        {{-- @foreach($data['products'] as $product)
        <td>{{Form::text($order->clientId . "_" . $product->name, object_get($order, "{$product->name}"),array('id'=>$order->clientId ))}} </td>


         @endforeach
         --}}
      {{-- </tr> --}}

  {{-- @endforeach --}}


</tbody>
</table>
</div>

 @endforeach

{{Form::submit('Submit')}}
{!! Form::close() !!}

      @endsection

      @section('js')
      <script src={{ asset('storage/js/sum.js') }}></script>
        <script src={{ asset('storage/js/isNumberKey.js') }}></script>
      <script src={{ asset('storage/js/deleteAlert.js') }}></script>







      @stop
