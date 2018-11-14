   <!-- Breadcrumbs-->
  <style>
  .returnsBg{
    background-color: #dc354547;
  }

 .totalsBg{
    background-color: #60ef7159;
  }
    .headcol {
      text-align: center;
      height: 49px;
    position: absolute;
    width: 15.3em;
    margin-left: -219px;
    background-color: white;
    z-index: 1;
    }
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

  <div class="table-responsive row centerTable col-12 ">
      <table class="clientInfo td-right heb  table" id="dataTable" width="100%" cellspacing="0">
        <tr>
            <td> {{$pdfData['supplier']->email}} : מייל</td>
        <td>   שם לקוח  :{{$pdfData['supplier']->name}}</td>
        </tr>
        <tr>
            <td>  כתובת :{{$pdfData['supplier']->address}}</td>
            <td> {{$pdfData['supplier']->id}}: מספר לקוח</td>

            </tr>
        <tr>
            <td> טלפון :{{$pdfData['supplier']->number}}</td>
            <td >{{$pdfData['to_date']}}   -<strong>  תאריך:</strong>
               {{$pdfData['from_date']}}<strong> - ל  </strong> </td>

          </tr>
        </table>
  </div>
        <br>
        <br>

        <div class="row  table-responsive col-12 ">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                <th>סך עלות ההזמנות </th>
                <th>סך עלות חסר </th>
                <th>חוב סופי</th>
                </tr>
              </thead>
              <tbody>
                <tr>
              <th  class="text-center"> <img  class="img" src="{{asset('storage/images/shekel.png')}}" >{{$pdfData['oSumCosts']}}</th>
              <th> <img  class="img" src="{{asset('storage/images/shekel.png')}}" >{{$pdfData['mSumCosts']}}</th>
              <th class="returnsBg"> <img  class="img" src="{{asset('storage/images/shekel.png')}}" >{{$pdfData['oSumCosts'] - $pdfData['mSumCosts']}}</th>
                </tr>
              </tbody>
              <tfoot>

              </tfoot>
            </table>

          </div>
          <br>

 @foreach ($pdfData['names'] as $page => $productArray)


  <div class="table-responsive">
      <table class="table-bordered table table-striped" id="dataTable" width="100%" cellspacing="0">

                  <thead>

                      <tr>
                        <th>תאריך</th>
        @foreach ($pdfData['names'][$page] as  $name => $id)

                      <th>{{$name}}</th>
                      <th class= "returnsBg">ח</th>
                    


        @endforeach
      </tr>
      </thead>
      <tbody>

              @foreach ($pdfData['orders'] as $date => $array)
              <tr>
              <th style="white-space:nowrap">{{$date}}</th>
              @foreach ($array[$page] as $id => $quantity)
              <th>{{$quantity}}</th>
              @if(array_key_exists($date,$pdfData['missingProducts']))
                @if(array_key_exists($page,$pdfData['missingProducts'][$date]))
              <th class= "returnsBg">{{$pdfData['missingProducts'][$date][$page][$id]}}</th>
              @endif
              @else
              <th class= "returnsBg">0</th>
              @endif
              @endforeach
            </tr>
            @endforeach



      </tbody>
      <tfoot>
        <tr>
        <th>סה"כ</th>
       @foreach ($pdfData['orderSums'][$page] as $id => $sum)
           <th style="white-space:nowrap" class="totalsBg">{{$sum}} </th>
        <th style="white-space:nowrap" class= "returnsBg">{{$pdfData['missingSums'][$page][$id]}}</th>
       @endforeach


        </tr>
        <tr>
            <th>   סה"כ תשלום</th>
           @foreach ($pdfData['totaloProductsCosts'][$page] as $id => $cost)
               <th style="white-space:nowrap" class="totalsBg"> <img  class="img" src="{{asset('storage/images/shekel.png')}}" >{{$cost}} </th>
            <th  style="white-space:nowrap" class= "returnsBg"> <img  class="img" src="{{asset('storage/images/shekel.png')}}" >{{$pdfData['totalmProductsCosts'][$page][$id]}}</th>
           @endforeach


            </tr>


      </tfoot>

      </table>
    </div>

    <pagebreak>
    @endforeach
