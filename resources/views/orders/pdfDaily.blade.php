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

<div class="logo" style="text-align:center"><h5 style="display:inline">משווק מנות בוקר</h5></div>

<style>
  tr:nth-child(even) {background: #CCC}
tr:nth-child(odd) {background: #FFF}
table, th, td {
   border: 1px solid black;


}


.table-responsive {

    }
</style>


@foreach ($data['clients'] as $routeNum => $clients)
@foreach ($data['products'] as $productType => $productPages)
@foreach ($productPages as $page => $products)

<div style="float:left;width:80%;">       פרשת: {{$data['parsha']}} יום: {{$data['day']}}</div>
<div style="float:right;width:20%;">  תאריך: {{$data['date']}}  </div>


  <div style="text-align:center; font-size:40px;">{{ucfirst(__('products.'.$productType))}}</div>
  <h4>Route{{$routeNum}}/{{count($data['clients'])}} | Page {{$page}}/{{count($productPages)}}</h4>


<div class="table-responsive ">

  <table class="table table-bordered" id="dataTable"  cellpadding="1px"  border="1" width="100%"  style=""  >
    <thead>
      <tr>
          <th width = "150px">לקוחות</th>
        @foreach ($products as $id => $name)
          <th >{{str_replace("_"," ",$name)}}</th>
        @endforeach
        <th >סה״כ</th>

</tr>
</thead>

<tbody>
  @foreach ($clients as  $client)
    @if (array_sum($client['qtys'][$productType]) > 0 )
      <tr>
          <td  style="overflow:nowrap; !important">{{$client['clientInfo']->name}}</td>
          @foreach ($products as $id => $name)
            @if (array_key_exists($id, $client['qtys'][$productType]))
                <td> {{$client['qtys'][$productType][$id]}}</td>
              @else
                  <td>0</td>
            @endif

          @endforeach
          <td>{{$client['clientSum']}}</td>
        </tr>
    @endif

  @endforeach


</tbody>
<tfoot>
  <tr>
      <th>לקוחות</th>
      @foreach ($products as $id => $name)
        <th>{{str_replace("_"," ",$name)}}</th>
      @endforeach
      <th>סה״כ</th>





  </tr>
  <tr>
    <th>סה"כ</th>
    @foreach ($products as $id => $name)
      <th>{{$data['sums'][$name]}}</th>
    @endforeach
<td>0</td>






</tr>
</tfoot>
</table>

</div>
<pagebreak>
@endforeach
@endforeach
@endforeach
