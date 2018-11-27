<style>
        tr:nth-child(even) {background: #CCC}
      tr:nth-child(odd) {background: #FFF}
      table, th, td {
         border: 1px solid black;

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
      <div class=""></div>
      @foreach($all as $data  )
      @if($data['orders'] == null )
      @continue
      @endif
     <div class=" heb wrapper">


       <h1 class=""><strong>הופמן </strong></h1>




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
                <th>    מחיר</th>
                <th>סה"כ</th>
                <th>חזרות</th>
                <th>כמות</th>

              <th class="" >מוצר</th>






      </tr>
      </thead>

      <tbody>


            @if (array_key_exists('invoiceInfo', $data))
              @foreach($data['invoiceInfo'] as  $name => $infoArray)

        <tr>

                <td> {{$infoArray['totalToPayForProduct']  - ($infoArray['totalToPayForProduct'] * .17) }}</td>

                <td>
                    @foreach($infoArray['price'] as $price => $amount)

                    @if(count((array)$infoArray['price']) > 1)
                   | {{$amount}} - <img  class="img" src="{{public_path('images/shekel.png')}}" > {{$price - ($price * 0.17)}} |
                   @else
                   {{$price - ($price * 0.17)}}
                   @endif
                    @endforeach
                  </td>

                <td>{{$infoArray['totalSold']}}</td>
                <td>{{$infoArray['returns']}}</td>
                <td>{{$infoArray['ordered']}}</td>

                <td>{{$name}}</td>
            </tr>




              @endforeach


       @endif

      </tbody>
      <tfoot>


        <tr class="table-danger">
            <td>{{$data['totalToPay']  - ($data['totalToPay'] * .17)}} </td>
          <td >סה"כ</td>


        </tr>
        <tr class="table-success">
          <td>{{$data['totalToPay'] * .17 }}</td>
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
      <td>{{$data['totalToPay'] * 1.17  }}</td>
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

    </div>

    <pagebreak>
    @endforeach
