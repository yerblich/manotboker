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

<h1><div style="text-align:center">הופמן</div></h1>

<div class=" heb wrapper">
<div class="">
   <h1 class=""><strong></strong></h1>
<h1 class=""><strong> </strong></h1>


  @if(!$data['orders'] == '')
    <div class="table-responsive row centerTable col-12 ">
        <table class="clientInfo td-right heb table  table" id="dataTable" width="100%" cellspacing="0">
          <tr>
            @if(array_key_exists("invoiceId",$data))
              <th>{{$data['invoiceId']}} : חשבונית מס</th>
              @else
                <th>000000 : חשבונית מס</th>
            @endif
          <th>    661519595 -  ע.מ</th>

          </tr>
          <tr>
              <td> {{$data['client']['email']}}: מייל</td>
          <td>   שם לקוח :{{$data['client']['name']}}</td>
          </tr>
          <tr>
              <td>{{$data['client']['address']}} : כתובת</td>
              <td> {{$data['client']['id']}}: מספר לקוח</td>

              </tr>
          <tr>
              <td>{{$data['client']['number']}}: טלפון</td>
              <td >{{$data['to_date']}}   -<strong>  תאריך:</strong>
                 {{$data['from_date']}}<strong> - ל  </strong> </td>

            </tr>
          </table><br>
          <br>
    <table class=" heb table table-bordered table" id="dataTable" width="100%" cellspacing="0">
      <thead class="thead-light">

        <tr class="">
            <th>סה"כ לתשלום</th>
            <th>  <img  class="img" src="{{asset('storage/images/shekel.png')}}" >  מחיר</th>
            <th>סה"כ</th>
            <th>חזרות</th>
            <th>כמות</th>

          <th class="" >מוצר</th>






  </tr>
  </thead>

  <tbody>



          @foreach($data['invoiceInfo'] as  $name => $infoArray)

    <tr>








            <td><img  class="img" src="{{asset('storage/images/shekel.png')}}" > {{$infoArray['totalToPayForProduct'] }}</td>

            <td>
                @foreach($infoArray['price'] as $price => $amount)

                @if(count((array)$infoArray['price']) > 1)
               | {{$amount}} - <img  class="img" src="{{asset('storage/images/shekel.png')}}" > {{$price}} |
               @else
               <img  class="img" src="{{asset('storage/images/shekel.png')}}" >{{$price}}
               @endif
                @endforeach
              </td>

            <td>{{$infoArray['totalSold']}}</td>
            <td>{{$infoArray['returns']}}</td>
            <td>{{$infoArray['ordered']}}</td>

            <td>{{$name}}</td>
        </tr>




          @endforeach




  </tbody>
  <tfoot>

<tr class="table-danger">
    <td><img  class="img" src="{{asset('storage/images/shekel.png')}}" >{{$data['totalToPay'] }} </td>
  <td >סה"כ</td>


</tr>
<tr class="table-success">
  <td><img  class="img" src="{{asset('storage/images/shekel.png')}}" >{{$data['totalToPay'] * .17 }}</td>
<td > 17%  מע״מ </td>


</tr>

@if($data['client']['credit']  > 0 )
<tr class="table-success">
    <td><img  class="img" src="{{asset('storage/images/shekel.png')}}" >{{$data['client']['credit'] }}</td>
    <td>    זכות </td>


</tr>

<tr class="table-success">
    <td><img  class="img" src="{{asset('storage/images/shekel.png')}}" >{{($data['totalToPay'] * 1.17) - $data['client']['credit'] }}</td>
  <td >     יתרה לתשלום </td>


</tr>
@elseif($data['client']['debt'] > 0 )

<tr class="table-danger">
  <td><img  class="img" src="{{asset('storage/images/shekel.png')}}" >{{$data['client']['debt']}}</td>
  <td>    חוב </td>


</tr>

<tr class="table-success">
  <td><img  class="img" src="{{asset('storage/images/shekel.png')}}" >{{($data['totalToPay'] * 1.17) + $data['client']['debt'] }}</td>
<td >    יתרה לתשלום </td>


</tr>
@else
  <tr class="table-success">
    <td><img  class="img" src="{{asset('storage/images/shekel.png')}}" >{{$data['totalToPay'] * 1.17  }}</td>
  <td >    יתרה לתשלום </td>


  </tr>
@endif

  </tfoot>
  </table>
</div>
<br>
  <table width="100%" border="0">
<tr>
  <thead>
<th> ________________:שם המקבל:________________ חתימה </th>

<th>{{date("d/m/Y")}}   :תאריך רישום</th>
</thead>
</tr>
  </table>

  @else
  <br>
  No orders
  @endif
</div>
