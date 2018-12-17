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

          <th> המאזן עד כה</th>
          <th>חוב מהחשבונית האחרונה</th>
          <th>לקוח</th>





      </tr>
</thead>
<tbody>
      @foreach ($allDebts as $clientName => $array)



        <tr class="">
          <th> {{$array['totalDebt']}} </th>

          <th>{{$array['lastInvoice']}} </th>
            <th>{{$clientName}}</th>



        </tr>

      @endforeach
  <tbody>
    </table>

</div>
