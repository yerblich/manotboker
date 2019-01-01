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

<h1><div style="text-align:center">הופמן</div><div style="text-align:center">חשבונית מס</div></h1>

<div class=" heb wrapper">
<div class="">



  @if(!$data['orders'] == '')
    <table id="dataTable" width="100%"   autosize="1" cellspacing="0">
      <tr class="topHeader">
        @if($data["isOriginal"] == true)
          <th>מקור </th>
          <th>{{$data['invoiceId']}} : חשבונית מס</th>
        @else
          <th>העתק </th>
          <th>{{$data['invoiceId']}} : חשבונית מס</th>
        @endif
      <th>    661519595 -  ע.מ</th>

      </tr>

    </table>
    <div class="table-responsive row centerTable col-12 ">
        <table class="clientInfo td-right heb table  table" id="dataTable" width="100%" cellspacing="0">

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
          <th>סה"כ </th>
          <th>מחיר ליח׳</th>
          <th>סה"כ כמות</th>
          <th>חזרות</th>
          <th>כמות באריזה</th>
          <th>אריזות</th>
          <th>מוצר</th>
          <th  >ברקוד</th>
            <th  >#</th>





  </tr>
  </thead>

  <tbody>



          @foreach($data['invoiceInfo'] as  $name => $infoArray)

            <tr>

                    <td> {{number_format($infoArray['totalToPayForProduct'] / 1.17 , 2) }}</td>

                    <td>
                        @foreach($infoArray['price'] as $price => $amount)

                        @if(count((array)$infoArray['price']) > 1)
                       | {{$amount}} -  {{number_format($price / 1.17/$infoArray['units'],2)}} |
                       @else
                       {{number_format($price / 1.17/$infoArray['units'],2)}}
                       @endif
                        @endforeach
                      </td>

                    <td>{{$infoArray['totalSold'] * $infoArray['units'] }}</td>
                    <td>{{$infoArray['returns']}}</td>
                    <td>{{$infoArray['units'] }}</td>
                    <td>{{$infoArray['ordered']}}</td>

                    <td  style="text-align: right;width:40%;">{{$name}}</td>
                    <td>{{$infoArray['barcode']}}</td>
                    <td>{{$infoArray['row']}}</td>
                </tr>




          @endforeach

          <tr class="table-danger">
              <td>{{number_format($data['totalToPay'] / 1.17 ,2)    }}  </td>
            <td >סה"כ לפני מע״ם</td>


          </tr>
          <tr class="table-success">
            <td>{{ number_format(($data['totalToPay'] /  1.17 ) * .17,2) }}</td>
          <td > 17%  מע״מ </td>


          </tr>
          <tr class="table-danger">
              <td>{{number_format($data['totalToPay'] ,2)    }}  </td>
            <td >סה"כ אחרי מע״ם</td>


          </tr>


  </tbody>
  <tfoot>



  </tfoot>
  </table>

  @if (max($data['prevProductsQty']) > 0)
    <br>
    <div style=" width:100%;">

    <table  id="dataTable" width="100%"   autosize="1" cellspacing="0">
      <thead>
        <tr>
          <th> זיכוי</th>
          <th>כמות</th>
          <th> חזרות מחודש קודם</th>


        </tr>
      </thead>

        @foreach ($data['prevProductsQty'] as $ProductName => $quantity)
          @if ($quantity > 0)
            <tr class="table-danger">
    <td>{{number_format($data['prevProductsCosts'][$ProductName])}}</td>
               <td>{{$quantity}}</td>
              <td>{{$ProductName}}</td>

            </tr>
          @endif

        @endforeach
<tfoot>
{{-- <tr>
  <td>{{number_format(array_sum(array_values($data['prevProductsCosts'])),2)}}</td>
<td>סה״כ זיכוי</td>


</tr> --}}
</tfoot>







    </table>
    </div>
  @endif

  <br/>
  <div style="float:left; width:50%;">
    <table id="dataTable" width="99%"   autosize="1" cellspacing="0">



      @if($data['client']['credit']  > 0 )
      <tr class="table-success">
          <td>{{$data['client']['credit'] }}</td>
          <td>    זכות </td>


      </tr>

      <tr class="table-success">
          <td>{{($data['totalToPay']) - $data['client']['credit'] - $data['totalReturnCredit']   }}</td>
        <td >     סה״כ לתשלום </td>


      </tr>
      @elseif($data['client']['debt'] > 0 )

      <tr class="table-danger">
        <td>{{$data['client']['debt']}}</td>
        <td>    חוב </td>


      </tr>

      <tr class="table-success">
        <td>{{$data['totalToPay'] + $data['client']['debt'] - $data['totalReturnCredit']  }}</td>
      <td >    סה״כ לתשלום </td>


      </tr>
      @else
        <tr class="table-success">
          <td>{{$data['totalToPay']  - $data['totalReturnCredit']  }}</td>
        <td >    סה״כ לתשלום </td>


        </tr>
      @endif


    </table>
</div>
<div style="text-align: right;float:right; width:49%;border:1px solid;border-radius:3px;height:150px;">
  &nbsp;&nbsp;
@if ($data['notes'] !== '')

  {{$data['notes']}}

@endif
&nbsp;&nbsp;&nbsp;&nbsp;
</div>


</div>
<br/>
  <br/>
<br/>
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
