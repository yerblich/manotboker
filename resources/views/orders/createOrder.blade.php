@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <style>
  .table
    {
      overflow: initial;

  /* margin-left:15em; */
    }
 .table tr th{
   white-space: nowrap;
 }

    thead th {
      position: sticky; top: 0; z-index: 100;
    }
    .datepicker{
      z-index: 1000;
    }




  </style>
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">צור הזמנה</a>
    </li>

  </ol>
  @if(empty($data['clientList']) &&  !empty($data['products']))
  <div class="text-right">
      <a class="btn btn-primary" href="{{ url('/clients')}}">
        <span class="nav-link-text">לקוחות</span>
      </a>
       לא נמצאו מוצרים, לחץ להוספה
    <br>
  </div>
  <br>
    <div class="text-right">
    <a class="btn btn-primary" href="{{ url('/products')}}">
      <span class="nav-link-text">מוצרים</span>
    </a>

    לא נמצאו לקוחות, לחץ להוספה
      </div>

  @elseif(!$data['clientList']  )
  <div> לא נמצאו לקוחות, לחץ להוספה
      <a class="btn btn-primary" href="{{ url('/clients')}}">
        <span class="nav-link-text">Clients</span>
      </a>
  </div>

  @elseif( !count((array)$data['products']) )
  <div> לא נמצאו מוצרים, לחץ להוספה
      <a class="btn btn-primary" href="{{ url('/products')}}">
        <span class="nav-link-text">Products</span>
      </a>

  </div>
@else


  {!! Form::open(['action'=> 'ordersController@create', 'method' => 'POST']) !!}
  {{ csrf_field() }}
 Populate : {{Form::text('populate', $data['populatedDate'], array('style' => 'position: relative; z-index: 1000;',  'name' => 'populate','class' => 'datepicker', 'autocomplete' => 'off'))}}
  {{Form::submit('לאכלס')}}
  {!! Form::close() !!}
  <hr>


  {!! Form::open(['action'=> 'ordersController@store', 'method' => 'POST','class' => 'form-group order' ]) !!}
 <div class="row form-group">
    <div class="col-4">{{Form::text('day', null, ['placeholder'=>'יום', 'class'=> 'text-right form-control','required' => 'required'])}}   </div>
<div class="col-4">  {{Form::text('parsha', null,['placeholder'=>'פרשת','class'=> 'text-right form-control','required' => 'required'])}}  </div>

 <div class = "col-4">{{Form::text('date', null, array('style' => 'position: relative; z-index: 1000;', 'placeholder'=>'תאריך','class' => 'text-right form-control datepicker', 'autocomplete' => 'off', 'required' => 'required'))}} </div>
</div><br/>

{{-- create table for each type of product i.e shabbos and daily  --}}
 @foreach($data['products'] as $orderType => $products)
 @if(count($data['products'][$orderType]))
   <div class="row">
     <div class="col-11"><h1>{{ ucfirst($orderType)}}</h1></div>
     <div class="col-1"><div id = {{$orderType}} onclick="return false"class="reset btn btn-primary">לאפס</div></div>
   </div>


 <div class="tableWrapper blueScroll table-responsive form-group">
  <table class="table-striped sticky table table-bordered " id="dataTable" width="100%" cellspacing="0">
    <thead class="thead-light">
      <tr>
          <th style="z-index: 1000;"class="headcol">Clients</th>

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
        <th class="headcol">Total</th>
        {{-- table footer --}}
        @foreach($products  as $product)


        <td >{{Form::text('sum_'.$product->id,'',['class' => 'sum_'.$product->id.'  form-control col-12 '.$orderType ,'style' => 'padding:1px; text-align:center','readonly'])}} </td>


      @endforeach
  <tr>
      <th class="headcol">לקוחות</th>
      {{-- table footer --}}
      @foreach($products  as $product)


      <th>{{$product->name}}</th>


    @endforeach
  </tr>
</tfoot>
<tbody>

  @foreach($data['clientList'] as $clientName => $orderInfo)

   <tr>
      <th style="min-height:52px;" class="headcol">{{$clientName}}</th>
      @if($orderInfo != null)

      @foreach($products as $product)
      @if(isset($orderInfo[$orderType][$product->id]))
      <td>{{Form::input('number',$data['clientIds'][$clientName]. "_" . $product->id, $orderInfo[$orderType][$product->id],[ 'onkeypress'=> "return isNumberKey(event)",'class' => 'amount_'. $product->id.' test form-control col-12 '.$orderType ,'style' => 'padding:1px; text-align:center'])}} </td>
   @else
   <td>{{Form::input('number',$data['clientIds'][$clientName]. "_" . $product->id, '',['onkeypress'=> "return isNumberKey(event)",'class' => 'amount_'.$product->id.' test form-control col-12 '.$orderType ,'style' => 'padding:1px; text-align:center'])}} </td>
      @endif
      @endforeach

      @else
      @foreach($products as $product)

      <td>{{Form::input('number',$data['clientIds'][$clientName]. "_" . $product->id,'',['onkeypress'=> "return isNumberKey(event)",'class' => 'amount_'.$product->id.' test form-control col-12 '.$orderType ,'style' => 'padding:0px;  text-align:center'])}} </td>
      @endforeach
      @endif


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

@endif
 @endforeach

{{Form::submit('שמור', ['class' => 'btn btn-primary  ' ,'id'=>"progress"] )}}
{!! Form::close() !!}

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


 @endif
      @endsection

      @section('js')
{{-- var surl = '{{url('/orders')}}'; --}}
{{-- <script>
var token = '{{Session::token()}}';
var url  = '{{ route("storeOrder")}}';

</script> --}}
         {{-- <script src={{ asset('storage/js/order.js') }}></script> --}}
      <script src={{ asset('storage/js/sum.js') }}></script>
        <script src={{ asset('storage/js/isNumberKey.js') }}></script>
<script src={{ asset('storage/js/reset.js') }}></script>



      @stop
