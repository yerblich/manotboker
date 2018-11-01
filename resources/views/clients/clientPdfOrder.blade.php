{{-- <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Easy Distribute</title>

  <link href="{{ asset('css/app.css') }}" media="all" rel="stylesheet">


  <link href="{{ asset('css/font-awesome.min.css') }}" media="all"  rel="stylesheet">

</head> --}}

<div class="logo" style="float:right"><h1>משווק מנות בוקר</h1></div><br><br><br>
Date: {{$data['pdf']->from_date}} To {{$data['pdf']->to_date}}
<style>
  tr:nth-child(even) {background: #CCC}
tr:nth-child(odd) {background: #FFF}
table, th, td {
   border: 1px solid black;

}
</style>
@if(!$data['pdf']->orders == '')
<div class="table-responsive">
<table class="table table-bordered table-responsive" id="dataTable" width="100%" cellspacing="0">
  <thead class="thead-light">
    <tr>
      <th>Date</th>
      @if($data['pdf']->productNames) !== '' )

      @foreach($data['pdf']->productNames as $productid => $name)
      
      
        <th>{{$name}}</th>
        <th>R</th>
      
      @endforeach
  
    @else 
      <p> לא נמצאו מוצרים </p>
    @endif
</tr>
</thead>

<tbody>


 
      @foreach($data['pdf']->allOrders as $orderDate => $orderAndReturns)
   <tr>
      <td>{{$orderDate }}</td>

          @foreach($orderAndReturns->orders as $productId => $qty)
          <td>{{$qty}}</td>
          @if(array_key_exists('returns', $orderAndReturns))
          <td class="table-danger">{{$orderAndReturns->returns->$productId}}</td>
          @else
          <td class="table-danger">0</td>
          @endif      
      
        
                  @endforeach
  </tr>
      @endforeach
    




</tbody>
<tfoot>
<tr > 
    <th class="table-success">סה"כ</th>
   
    
      

           @foreach($data['pdf']->productOrderTotals as $product)
                   
                   <td class="table-success">{{$product->orders}}</td>
                   
         <td class="table-danger">{{$product->returns}}</td>
         
                   @endforeach
  
      
</tr>
<tr>
      <th>תאריך</th>
     
      @if($data['pdf']->productNames) !== '' )

  @foreach($data['pdf']->productNames as $productid => $name)
  
  
    <th>{{$name}}</th>
    <th>ח</th>

  
  @endforeach

@else 
  <p> לא נמצאו מוצרים </p>
@endif
</tr>
</tfoot>
</table>
</div> 
@else 
<br>
לא נמצאו הזמנות
@endif
