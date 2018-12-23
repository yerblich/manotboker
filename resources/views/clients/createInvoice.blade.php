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
  <div style="display:none" class="row">

 <div  class="col-md-5 text-right"> {!! Form::open(['action'=>[ 'InvoiceController@store', $data['client']->id ],'onSubmit' =>' $(window).unbind("beforeunload")','id'=> 'postPrint','method' => 'POST']) !!}
    {{ csrf_field() }}

    {!! Form::hidden('data', json_encode($data,TRUE)) !!}

    {{Form::submit('save',  ['name' => 'paid','class' => 'btn btn-primary'])}}

    {!! Form::close() !!}</div>
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

<div class="row">




    <div class="col-12  ">
      <button id= "printInvoice" class="button btn-primary">הדפס</button>
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
<object  data="{{asset("storage/pdfInvoices/invoicePreview.pdf#toolbar=0")}}" type="application/pdf" width="100%" height="500">
  <iframe id = "pdfFrame" name="pdfFrame" src="{{asset("storage/pdfInvoices/invoicePreview.pdf")}}" width="100%" height="600"></iframe>
</object>
</div>
</div>
<div id="modalbar" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>

      </div>
      <div class="modal-body">

        Print Success ? press yes to confirm
        <button onclick="printSuccess()"> Yes </button> <button onclick="printFail()"> No </button>

      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
      @endsection
      @section('js')


      <script src={{ asset('storage/js/checkSave.js') }}></script>
      <script src={{ asset('storage/js/printInvoice.js') }}></script>
      @stop
