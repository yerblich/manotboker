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

<div class="logo" style="float:right"><h1>משווק מנות בוקר</h1></div>

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

  <br>
  <h1 style="text-align:center;">{{ucfirst(__('products.'.$productType))}}</h1>
  <h4>Route{{$routeNum}}/{{count($data['clients'])}} | Page {{$page}}/{{count($productPages)}}</h4>


<div class="table-responsive ">

  <table class="table table-bordered" id="dataTable"  cellpadding="1px" autosize="1" border="1" width="100%" style="overflow: wrap" >
    <thead>
      <tr>
          <th>לקוחות</th>
        @foreach ($products as $id => $name)
          <th>{{$name}}</th>
        @endforeach


</tr>
</thead>

<tbody>
  @foreach ($clients as  $client)
    
    <tr>
        <td>{{$client['clientInfo']->name}}</td>
        @foreach ($products as $id => $name)
            <td> {{$client['qtys'][$id]}}</td>
        @endforeach

      </tr>
  @endforeach


</tbody>
<tfoot>
  <tr>
      <th>לקוחות</th>
      @foreach ($products as $id => $name)
        <th>{{$name}}</th>
      @endforeach






  </tr>
  <tr>
    <th>סה"כ</th>
    @foreach ($products as $id => $name)
      <th>{{$data['sums'][$name]}}</th>
    @endforeach







</tr>
</tfoot>
</table>

</div>
<pagebreak>
@endforeach
@endforeach
@endforeach
