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
@if ($data['creditType'] == 'credit')
   <h1><div style="text-align:center">חשבונית זיכוי</div></h1>
@else
 <h1><div style="text-align:center">זיכוי</div></h1>

@endif

<div class=" heb wrapper">
<div class="">



  @if(!$data['currentCredit'] == '')
     @if ($data['creditType'] == 'credit')
    <table id="dataTable" width="100%"   autosize="1" cellspacing="0">
      <tr class="topHeader">
        @if($data["originality"] == 'original')
          <th>מקור </th>
          <th>{{$data['currentCredit']->credit_num}} : חשבונית זיכוי מס׳</th>
        @elseif($data["originality"] == 'originalCopy')
          <th>העתק נאמן למקור </th>
          <th>{{$data['currentCredit']['creditNum']}} : חשבונית זיכוי מס׳</th>
        @else
          <th>העתק </th>
        <th>{{$data['currentCredit']['creditNum']}} : חשבונית זיכוי מס׳</th>
        @endif
      <th>    661519595 -  ע.מ</th>

      </tr>

    </table>
  @endif
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

          </table><br>
          <br>
    <table class=" heb table table-bordered table" id="dataTable" width="100%" cellspacing="0">
      <thead class="thead-light">

        <tr class="">
          <th>סה"כ </th>
          <th>מחיר ליח׳</th>
          <th>תאור</th>
          <th>כמות</th>
            <th  >#</th>





  </tr>
  </thead>

  <tbody>


@php ($row = 1)
          @foreach($data['creditItems'] as  $creditItemRow)

            <tr>

                    <td> {{$creditItemRow['total_credit']}}</td>
                    <td> {{$creditItemRow['unit_price']}}</td>
                    <td> {{$creditItemRow['description']}}</td>
                    <td> {{$creditItemRow['product_amount']}}</td>
                    <td> {{$row}}</td>
                </tr>

@php ($row++)


          @endforeach

          <tr class="table-danger">
              <td>{{$data['total_credit']   }}  </td>
            <td >סה"כ </td>


          </tr>


  </tbody>
  <tfoot>



  </tfoot>
  </table>


  <br/>


</div>

{{-- <div style="text-align: right;float:right; width:49%;border:1px solid;border-radius:3px;height:150px;">

  &nbsp;&nbsp;
@if ($data['notes'] !== '')

  {{$data['notes']}}

@endif
&nbsp;&nbsp;&nbsp;&nbsp;
</div> --}}
@if (count($data['creditItems']) > 17)
  <pagebreak>
@endif


</div>
<br>

<br/>
  <table width="100%" border="0">
<tr>
  <thead>
<th> ________________:שם המקבל:________________ חתימה </th>

<th> {{$data['date']}}  :תאריך רישום</th>
</thead>
</tr>
  </table>

  @else
  <br>
  No orders
  @endif
</div>
