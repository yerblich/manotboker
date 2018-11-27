@extends('layouts.main')

@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#"></a>
      {{-- {{$data['message']}} --}}
    </li>
    <style>
        tr:nth-child(even) {background: #CCC}
      tr:nth-child(odd) {background: #FFF}
      table, th, td {
         border: 1px solid black;

      }
      @font-face {
	font-family: 'Ezra SIL SR';
	src: url('fonts/ezrasilsr-webfont.eot?#iefix') format('embedded-opentype'),
	     url('fonts/ezrasilsr-webfont.woff') format('woff'),
	     url('fonts/ezrasilsr-webfont.ttf')  format('truetype'),
	     url('fonts/ezrasilsr-webfont.svg#EzraSILSR') format('svg');
    }
    .test{
        font-family: 'Ezra SIL SR'
    }
    </style>
  </ol>
  <div class="row">

<!-- <div  class="col-md-5 text-right"> {!! Form::open(['action'=>[ 'InvoiceController@update', $data['client']->id ],'method' => 'POST']) !!}
    {{ csrf_field() }}

    {!! Form::hidden('data', json_encode($data,TRUE)) !!}
    {!! Form::text('amountPaid') !!}
    {{Form::submit(' שולם',  ['name' => 'paid','class' => 'btn btn-primary'])}}
    @method('PUT')
    {!! Form::close() !!}</div> -->
</div>

<hr>
<div class="card clientInfo ">
  <div class="row justify-content-end p-4">
      <div class="col-md-2  text-right clientInfo">
          <h2>מאזן</h2>
          <ul style = "direction:rtl;" class="">
              חוב: {{$data['client']->debt}}<br>
              זכות: {{$data['client']->credit}}
           </ul>
        </div>
    <div class="col-md-3  text-right">
      <h2>כתובת </h2>
      <ul style = "direction:rtl;" class="list-group">
          עיר: {{$data['client']->city}}<br>
          רחוב: {{$data['client']->address}}<br>

      </ul>
      </div>
    <div class="col-md-4  text-right">
          <h2>אנשי קשר</h2>
          <ul style = "direction:rtl;" class="list-group">
              מייל: {{$data['client']->email}}<br>
              טל: {{$data['client']->number}}<br>
          </ul>
        </div>

        <div class="col-md-3 text-right ">
            <h2>פרטים </h2>
            <ul style = "direction:rtl;" class="list-group-flush">
               שם: {{$data['client']->name}}<br>
               מספר לקוח:  {{$data['client']->id}}
            </ul>
          </div>
      </div>
</div>

<br>


  <div class="table-responsive row centerTable col-12 ">
  <table class="table table-bordered table" id="dataTable" width="100%" cellspacing="0">
    <thead class="thead-light">
      <tr>
        <th>מוצר</th>
        <th>כמות</th>
        <th>חזרות</th>
        <th>סה"כ</th>
        <th>מחיר</th>
        <th>סה"כ לתשלום</th>

</tr>
</thead>

<tbody>



        @foreach($data['invoiceInfo'] as $name => $infoArray)
        <tr>


          <td>{{$name}}</td>





                      <td>{{$infoArray['ordered']}}</td>
                      <td>{{$infoArray['returns']}}</td>
                      <td>{{$infoArray['totalSold']}}</td>
                      <td>
                        @foreach($infoArray['price'] as $price => $amount)

                        @if(count($infoArray['price']) > 1)
                       | {{$amount}} - &#8362;{{$price - ($price * .17)}} |
                       @else
                       &#8362;{{$price - ($price * .17)}}
                       @endif
                        @endforeach
                      </td>
                      <td> &#8362;{{$infoArray['totalToPayForProduct'] - ($infoArray['totalToPayForProduct'] * .17) }}</td>
            {{-- <td class="table-danger">{{$order->return[$product->name]}}</td> --}}


      </tr>




        @endforeach




</tbody>
<tfoot>

  <tr class="table-danger">
    <th >סה"כ</th>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
  <td>&#8362;{{$data['totalToPay']  - ($data['totalToPay'] * .17)}} </td>

  <tr class="table-danger">
    <td > 17%  מע״מ </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>&#8362;{{$data['totalToPay'] * .17 }}</td>



  </tr>


</tr>

@if($data['client']->credit  > 0 )
<tr class="table-success">
    <td >    זכות </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
<td>&#8362;{{$data['client']->credit }}</td>
</tr>
<tr class="table-danger">
    <td >    יתרה </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>&#8362;{{$data['totalToPay'] - $data['client']['credit'] }}</td>



</tr>
@elseif($data['client']->debt  > 0)
<tr class="table-danger">
    <td >    חוב קודם </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>&#8362;{{$data['client']->debt}}</td>

</tr>

<tr class="table-danger">
    <td >    סה"כ לתשלום  </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>&#8362;{{$data['totalToPay']  + $data['client']['debt']  }}</td>
</tr>
@else
  <tr class="table-success">
      <td >    יתרה לתשלום </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>&#8362;{{$data['totalToPay'] }}</td>
  <td >    יתרה לתשלום </td>


  </tr>

@endif
{{--
<tr class="table-success">
    <th >שולם </th>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td>&#8362;</td>

</tr>

<tr class="table-danger">
    <th >  יתרה לתשלום</th>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td>&#8362;</td>

     </tr>





    <tr class="table-success">
        <th >    זכות </th>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td>&#8362;</td>




    </tr>







    <tr class="table-danger">
      <th >חוב קודם</th>
     <td></td>
     <td></td>
     <td></td>
     <td></td>
     <td>&#8362;</td>




   <tr class="table-danger">
      <th >סה"כ לתשלום</th>
     <td></td>
     <td></td>
     <td></td>
     <td></td>
     <td>&#8362; </td>

  </tr> --}}








  <tr>
    <th>מוצר</th>
   <th>כמות</th>
    <th>חזרות</th>
    <th>סה"כ</th>
    <th>מחיר</th>
    <th>סה"כ לתשלום</th>

</tr>
</tfoot>
</table>
</div>

<br>
<div class="row">




    <div class="col-12  ">
      <div class="float-right ">
      {!! Form::open(['action'=>[ 'InvoiceController@store', $data['client']->id ],'method' => 'POST','onSubmit' =>' $(window).unbind("beforeunload")']) !!}
      {{ csrf_field() }}

      {!! Form::hidden('data', json_encode($data,true)) !!}

      {{Form::submit('Save',  ['name' => 'save','class' => 'progressMI btn btn-primary'])}}
      {{Form::submit('שלח Pdf',  ['name' => 'send','class' => 'progressMI btn btn-primary'])}}
      {!! Form::close() !!}
      </div>

    </div>
{{-- Download --}}
{{-- <div class="col-4 text-center">
      {!! Form::open(['action'=> ['InvoiceController@pdfDownload', $data['client']->id], 'method' => 'POST']) !!}
      {{ csrf_field() }}

      {!! Form::hidden('pdf', json_encode($data,TRUE)) !!}

      {{Form::submit('Download Pdf', ['class' => 'btn btn-primary'])}}

      {!! Form::close() !!}
    </div> --}}

{{-- Send --}}






    </div>
</div>
<br>
<div class="row">

  <div class="col-12">
<object  data="{{asset("storage/pdfInvoices/invoicePreview.pdf")}}" type="application/pdf" width="100%" height="500">
  <iframe src="{{asset("storage/pdfInvoices/invoicePreview.pdf")}}" width="100%" height="600"></iframe>
</object>
</div>
</div>
<div id="modalbar" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">טעינה</h5>

      </div>
      <div class="modal-body">

        <div style="display:none" id="progressbar"></div>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
      @endsection
      @section('js')


      <script src={{ asset('storage/js/checkSave.js') }}></script>
      @stop
