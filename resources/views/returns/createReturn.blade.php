@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">ליצור הזמנה</a>
    </li>
   
  </ol>
  
 

 
 

  {!! Form::open(['action'=> 'ReturnsController@store', 'method' => 'POST'],['class' => 'form-group']) !!}
 <div> תאריך:  {{Form::text('date', null, array('class' => 'datepicker', 'autocomplete' => 'off', 'required' => 'required'))}} Parsha:  {{Form::text('parsha', null)}}</div><br/>

  <div class="table-responsive form-group">
  <table class="table table-bordered table-responsive" id="dataTable" width="100%" cellspacing="0">
    <thead class="thead-light">
      <tr>
          <th>לקוחות</th>

          {{-- itirate through alll products and create header  --}}
  @if(count($data['products']) > 0 )

    @foreach($data['products'] as $product)
    
    
      <th>{{$product->name}}</th>

    
    @endforeach

  @else 
    <p> No Products </p>
  @endif
</tr>
</thead>
<tfoot>
  <tr>
      <th>לקוחות</th>
      {{-- table footer --}}
      @foreach($data['products'] as $product)
    
    
      <th>{{$product->name}}</th>

    
    @endforeach
  </tr>
</tfoot>
<tbody>

  @foreach($data['clientList'] as $client)
  
   <tr>
      <td>{{$client->name}}</td>
      @if($client != null)
      @foreach($data['products'] as $product)
      
      <td>{{Form::text($client->id. "_" . $product->id, '',['class' => 'form-control col-12' ,'style' => 'padding:1px; text-align:center'])}} </td>
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
{{Form::submit('שלח')}}
{!! Form::close() !!}
 
      @endsection
    