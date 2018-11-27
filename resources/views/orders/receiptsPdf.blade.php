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
text-align:center;

}
.td-right td{
  text-align:center;

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
| 0527135921a@gmail.com |
</div><br>
  <div class="table-responsive ">
    <table class="clientInfo td-right heb table  table" id="dataTable" width="100%" cellspacing="0">
      <tr>
          <td> {{$array['clientInfo']['email']}} : מייל</td>
      <td>   שם לקוח : {{$array['clientInfo']['name']}}</td>
      </tr>
      <tr>
          <td>  כתובת: {{$array['clientInfo']['address']}}</td>
          <td> {{$array['clientInfo']['id']}}: מספר לקוח</td>

          </tr>
      <tr>
          <td>{{$array['clientInfo']['number']}}: טלפון</td>
          <td >{{$array['orderInfo']['date']->format('d-m-Y')}}   <strong>: תאריך</strong>


        </tr>
      </table><br>
      <br>
  </div>


<div class="table-responsive ">

  <table class=" pdf table  td-right table-bordered" id="dataTable" width="100%" cellspacing="0" autosize="1">
    <thead>
      <tr >
          <th>סה״כ לתשלום </th>
          <th>מע״מ</th>
          <th>מחיר ליחידה</th>
          <th>סה״כ כמות</th>
          <th>כמות באריזה</th>
          <th>אריזות</th>
          <th>מוצרים</th>






</tr>
</thead>

<tbody>

@foreach ($array['products'] as $name => $array)
  <tr>
      <td>{{$array['totalUnits'] * $array['unitCost']}}</td>
      <td>{{$array['unitCost'] * .17}}</td>
      <td>{{$array['unitCost']- ($array['unitCost'] * .17)}}</td>
      <td>{{$array['totalUnits']}}</td>
      <td>{{$array['units']}}</td>
      <td>{{$array['qty']}}</td>
      <td>{{$name}}</td>




    </tr>

@endforeach

<tr>

  <th>{{$orders[$clientName]['totalCost']}}</th>
  <th>סה״כ</th>
</tr>



</tbody>
<tfoot>

  {{-- <tr>

    <td>{{array_sum($array['products'])}}</td>
<td>סה"כ</td>
</tr> --}}
</tfoot>
</table>

</div>
<pagebreak>
@endforeach
