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



<style>
  tr:nth-child(even) {background: #CCC}
tr:nth-child(odd) {background: #FFF}
table, th, td {
   border: 1px solid black;


}
body{
  font-size:12pt;


}
.td-right th{
  text-align:right;

}
.clientInfo td{
  width: 50%;
  font-size:12pt

}
.img{
  width:2%;
  height:2%;
}

</style>


{{-- <div style="float:left;width:80%;">       פרשת: {{$data['parsha']}} יום: {{$data['day']}}</div>
<div style="float:right;width:20%;">  תאריך: {{$data['date']}}  </div> --}}

  <br>

@foreach ($orders as $clientName => $array)

<div class="logo" style="text-align: center; "> <h1>הופמן עוגות אמריקאיות</h1>
@if ($typeOfDocument == 'quote')
  <h2><div>הצעת מחיר</div></h2>
@else
  <h2><div>תעודת משלוח</div></h2>
@endif

<div>| 0527135921a@gmail.com |</div>
</div><br>
  <div class="table-responsive ">
    <table class="clientInfo td-right hebtable  table" id="dataTable" width="100%" cellspacing="0">
      <tr>
          <th> {{$array['clientInfo']['email']}} : מייל</th>
      <th>   שם לקוח : {{$array['clientInfo']['name']}}</th>
      </tr>
      <tr>
          <th> : כתובת {{$array['clientInfo']['thress']}}</th>
          <th> {{$array['clientInfo']['th']}}: מספר לקוח</th>

          </tr>
      <tr>
          <th>{{$array['clientInfo']['number']}}: טלפון</th>
          <th >{{$array['orderInfo']['date']->format('d-m-Y')}}   <strong>: תאריך</strong></th>


        </tr>
      </table><br>
      <br>
  </div>


<div class="table-responsive ">

  <table class=" pdf table  td-right table-bordered" id="dataTable" width="100%" cellspacing="0" autosize="1">
    <thead>
      <tr >
          <th>סה״כ </th>
          <th>מחיר ליח׳ אחרי מע''מ</th>
          <th>מחיר ליח׳</th>
          <th>סה״כ כמות</th>
          <th>כמות באריזה</th>
          <th>אריזות</th>
          <th>מוצרים</th>
            <th>ברקוד</th>






</tr>
</thead>

<tbody>

@foreach ($array['products'] as $name => $array)
  <tr>
      <td>{{$array['totalUnits'] * $array['unitCost']}}</td>
      <td>{{$array['unitCost'] / $array['units'] }}</td>
      <td>{{number_format($array['unitCost'] / 1.17,2)}}</td>
      <td>{{$array['totalUnits']}}</td>
      <td>{{$array['units']}}</td>
      <td>{{$array['qty']}}</td>
      <td>{{$name}}</td>
      <td>{{$array['barcode']}}</td>




    </tr>

@endforeach





</tbody>
<tfoot>

  {{-- <tr>

    <td>{{array_sum($array['products'])}}</td>
<td>סה"כ</td>
</tr> --}}
</tfoot>
</table>
<br>
<table id="dataTable" width="50%" cellspacing="0" autosize="1">
  <tr>

    <th>{{$orders[$clientName]['totalCost']}}</th>
    <th>סה״כ לתשלום </th>
  </tr>
</table>

</div>
<pagebreak>
@endforeach
