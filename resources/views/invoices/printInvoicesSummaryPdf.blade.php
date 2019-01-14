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





    <table id="dataTable" width="100%"   autosize="1" cellspacing="0">
<thead>
      <tr class="">

          <th> סה״כ לתשלום</th>
          <th>תאריך רישום</th>
          <th>לקוח</th>
          <th>חשבונית מס׳</th>





      </tr>
</thead>
<tbody>
      @foreach ($invoicesList as $invoiceNum => $array)



        <tr class="">
          <th> {{$array['total']}} </th>

          <th>{{$array['date']}} </th>
            <th>{{$array['clientName']}} </th>
            <th>{{$invoiceNum}}</th>



        </tr>

      @endforeach
  <tbody>
    </table>

</div>
