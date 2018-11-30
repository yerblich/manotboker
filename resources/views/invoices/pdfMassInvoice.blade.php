<style>
        tr:nth-child(even) {background: #CCC}
      tr:nth-child(odd) {background: #FFF}
      table, th, td {
         border: 1px solid black;
         white-space: nowrap;
         padding: 2px;

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
      .topHeader th{
          width: 33%
      }
      .table{

      }
      .tfooter{

        border:none !important;
      }

      </style>

      @foreach($all as $data  )
      {{-- @if($data['orders'] == null )
      @continue
      @endif --}}
     <div class=" hebwrapper">


       <h1 class=""><strong>הופמן </strong></h1>
<table id="dataTable" width="100%"   autosize="1" cellspacing="0">
  <tr class="topHeader">
    @if($data["isOriginal"] == true)
      <th> מקור</th>
      <th>{{$data['invoiceId']}} : חשבונית מס</th>
    @else
      <th> העתק</th>
      <th>{{$data['invoiceId']}} : חשבונית מס</th>

    @endif
  <th>    661519595 -  ע.מ</th>

  </tr>

</table>



        <div class="table-responsive row centerTable col-12 ">
            <table class="clientInfo td-right heb  table" id="dataTable" width="100%"   autosize="1" cellspacing="1px">

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
        <table class=" heb table   " id="dataTable" width="100%" autosize="1" cellspacing="0" style="">
          <thead class="thead-light">

            <tr >
                <th>סה"כ </th>
                <th>מחיר ליח׳</th>
                <th>סה"כ כמות</th>
                <th>חזרות</th>
                <th>כמות באריזה</th>
                <th>אריזות</th>
                <th>מוצר</th>






      </tr>
      </thead>

      <tbody>


            @if (array_key_exists('invoiceInfo', $data))
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

                <td>{{$infoArray['totalSold']}}</td>
                <td>{{$infoArray['returns']}}</td>
                <td>{{$infoArray['units'] }}</td>
                <td>{{$infoArray['ordered']}}</td>

                <td  style="text-align: right;width:40%;">{{$name}}</td>
            </tr>




              @endforeach


       @endif

      </tbody>
      <tfoot>



      </tfoot>
      </table>
      <br>
      <table id="dataTable" width="50%"   autosize="1" cellspacing="0">

        <tr class="table-danger">
            <td>{{number_format($data['totalToPay'] / 1.17,2)}} </td>
          <td >סה"כ</td>


        </tr>
        <tr class="table-success">
          <td>{{number_format(($data['totalToPay'] /1.17 ) * .17,2)}}</td>
        <td > 17%  מע״מ </td>


        </tr>

        @if($data['client']['credit']  > 0 )
        <tr class="table-success">
          <td>{{$data['client']['credit'] }}</td>
          <td>    זכות </td>


        </tr>

        <tr class="table-success">
          <td>{{($data['totalToPay']) - $data['client']['credit'] }}</td>
        <td >     יתרה לתשלום </td>


        </tr>
        @elseif($data['client']['debt'] > 0 )

        <tr class="table-danger">
        <td>{{$data['client']['debt']}}</td>
        <td>    חוב </td>


        </tr>

        <tr class="table-success">
        <td>{{$data['totalToPay'] + $data['client']['debt'] }}</td>
        <td >    יתרה לתשלום </td>


        </tr>
        @else
        <tr class="table-success">
        <td>{{$data['totalToPay'] }}</td>
        <td >    יתרה לתשלום </td>


        </tr>
        @endif


</table>
      </div>
      <br>
        <table class="tfooter" width="100%" cellspacing="1"style=" border:none !important;">
      <tr>
        <thead>
      <th> ________________:שם המקבל:________________ חתימה </th>

      <th>{{date("d/m/Y")}}   :תאריך רישום</th>
      </thead>
      </tr>
        </table>

    </div>

    <pagebreak>
    @endforeach
