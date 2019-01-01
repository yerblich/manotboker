
<style>
    tr:nth-child(even) {background: #CCC}
  tr:nth-child(odd) {background: #FFF}
  table, th, td {
     border: 1px solid black;
     white-space: nowrap;
  }
  .img{
    width:2%;
    height:2%;
  }
  .td-right td{
    text-align:right;
    border:none;
  }
  .clientInfo td{
    width: 50%;

  }

  </style>
<hr>


{{-- <div class="border 1px col-md-2" >Date: {{ $data['date'] }}</div> --}}
<br>
<div class="row">
  <div class="col-md-4 ">
    שם לקוח:   {{$data['client']->name}}<br>
    מספר לקוח:  {{$data['client']->id}}
  </div>




</div>
@foreach ($data['pagedNames'] as $page => $array)
  @if(count($data['orders']) > 0)
    <div class="table-responsive">
    <table  class="table table-bordered " autosize="1.6" id="dataTable" width="100%" cellspacing="0">
      <thead class="thead-light">
        <tr>

          @if(count($data['pagedNames']) > 0 )

          @foreach($data['pagedNames'][$page] as $productid => $name)

            <th>ח</th>
            <th>{{$name}}</th>


          @endforeach

        @else
          <p> לא נמצאו מוצרים </p>
        @endif
          <th>תאריך</th>
  </tr>
  </thead>

  <tbody>


{{--  --}}
    @foreach($data['pagedOrders'] as $orderDate => $orderAndReturns)

       <tr>



                        @foreach($orderAndReturns['orders'][$page] as $productId => $qty)

                    @if(array_key_exists('returns', $orderAndReturns ))
                      <td class="table-danger">{{$orderAndReturns['returns'][$page][$productId]}}</td>

                      @else
                      <td class="table-danger">0</td>
                      @endif
                      <td>{{$qty}}</td>
                      @endforeach

                      <td style="white-space:nowrap">{{$orderDate}}</td>

      </tr>
      @endforeach





  </tbody>
  <tfoot>
    <tr >





               @foreach($data['pagedOrderTotals'][$page] as $product)
                 <td class="table-danger">{{$product['returns']}}</td>
                       <td class="table-success">{{$product['orders']}}</td>



                       @endforeach
<th class="table-success">סה"כ</th>

    </tr>
    <tr>


      @if(count($data['pagedNames']) > 0 )

      @foreach($data['pagedNames'][$page] as $productid => $name)
  <th>ח</th>

        <th>{{$name}}</th>



      @endforeach

    @else
      <p> לא נמצאו מוצרים </p>
    @endif
      <th>תאריך</th>
    </tr>
  </tfoot>
  </table>
  </div>
  @else
  <br>
  לא נמצאו הזמנות
  @endif
  <pagebreak>
@endforeach
@if (count($data['prevReturnsArray']) > 0)

    <div style=" width:100%;">
<h3 style="text-align:center">חזרות מחודש קודם</h3>
    <table id="dataTable" width="100%"   autosize="1" cellspacing="0">
      <thead>
        <tr>


          @foreach ($data['prevProductsNames']  as $ProductName => $productId)



                <th>{{$ProductName}}</th>



          @endforeach




<th>תאריך קבלה </th>
        </tr>
      </thead>
      <tbody>
         @foreach ($data['prevReturnsArray'] as $date => $array)
@if (max(array_values($array)) > 0)
  <tr class="table-danger">

@foreach ($array as $ProductName => $quantity)
@if ($quantity > 0)
   <td>{{$quantity}}</td>
@endif







@endforeach
  <td>{{$date}}-קיבלו ב</td>
</tr>
@endif


  @endforeach
<tbody>
  <tfoot>
<tr>
@foreach ($data['prevProductsTotals'] as $name => $total)
  @if ($total > 0)
<td>{{$total}}</td>
  @endif

@endforeach
<td>סה״כ</td>
</tr>

  </tfoot>




    </table>
    </div>

@endif
