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
table{


}
</style>

@foreach($data['route'] as $orderType  => $route)
 @foreach($route as $routeNum => $clientOrder)
@foreach($data['productNameArray'][$orderType] as $pageNum  => $nameArray)
<div style="float:left;width:80%;">       פרשת: {{$data['parsha']}} יום: {{$data['day']}}</div>
<div style="float:right;width:20%;">  תאריך: {{$data['date']}}  </div>

  <br>
<h2>{{ucfirst(__('products.'.$orderType)) }}</h2>  Route :{{$routeNum}}/{{count($data['route'][$orderType])}}  -    Page: {{$pageNum}}/{{count($data['productNameArray'][$orderType])}}

<div class="table-responsive ">

  <table class="table table-bordered" id="dataTable" width="100px" cellspacing="0" autosize="1">
    <thead>
      <tr>
          <th>לקוחות</th>
  @if(count($data['productNames'][$orderType]) > 0 )

    @foreach($nameArray as  $name)


      <th>{{$name}}</th>


    @endforeach

  @else
    <p> No Products </p>
  @endif
</tr>
</thead>

<tbody>

    @foreach($clientOrder as $client => $products)
    @if(max($clientOrder[$client]['products'][$pageNum]) > 0)
    <tr>

        <td>{{$client}}</td>

        @foreach($products as $array => $pages )
        @foreach($pages[$pageNum] as  $product => $qty)

        <td> {{$qty}} </td>

        @endforeach
         @endforeach

      </tr>
      @endif
  @endforeach


</tbody>
<tfoot>
  <tr>
      <th>לקוחות</th>
      @foreach( $nameArray as  $name)


      <th>{{$name}}</th>


    @endforeach
  </tr>
  <tr>
    {{-- <th>סה"כ</th>
    @foreach( $nameArray as  $name)


    <th>{{$data['sums'][$orderType][$pageNum][$name]}}</th>


  @endforeach

</tr> --}}
</tfoot>
</table>

</div>
<pagebreak>
@endforeach
@endforeach
@endforeach
