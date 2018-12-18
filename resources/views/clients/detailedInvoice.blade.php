
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
  @if(!$data['orders']->all() == '')
    <div class="table-responsive">
    <table  class="table table-bordered " autosize="1.6" id="dataTable" width="100%" cellspacing="0">
      <thead class="thead-light">
        <tr>
          <th>Date</th>
          @if(count($data['pagedNames']) > 0 )

          @foreach($data['pagedNames'][$page] as $productid => $name)


            <th>{{$name}}</th>
            <th>ח</th>

          @endforeach

        @else
          <p> לא נמצאו מוצרים </p>
        @endif
  </tr>
  </thead>

  <tbody>


{{--  --}}
    @foreach($data['pagedOrders'] as $orderDate => $orderAndReturns)

       <tr>
        <td style="white-space:nowrap">{{$orderDate}}</td>


                        @foreach($orderAndReturns['orders'][$page] as $productId => $qty)
                      <td>{{$qty}}</td>
                    @if(array_key_exists('returns', $orderAndReturns ))
                      <td class="table-danger">{{$orderAndReturns['returns'][$page][$productId]}}</td>

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




               @foreach($data['pagedOrderTotals'][$page] as $product)

                       <td class="table-success">{{$product['orders']}}</td>

    <td class="table-danger">{{$product['returns']}}</td>

                       @endforeach


    </tr>
    <tr>
          <th>Date</th>

      @if(count($data['pagedNames']) > 0 )

      @foreach($data['pagedNames'][$page] as $productid => $name)


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
  <pagebreak>
@endforeach
