@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{ url('/invoices')}}">חשבוניות</a>
    </li>

        <li class="breadcrumb-item">
          <a href="#">חשבוניות המוניות</a>
        </li>

  </ol>
  <div class="row">




        <div class="col-12  ">
          <div class="float-right ">
          {!! Form::open(['action'=> 'InvoiceController@saveMassInvoice','onSubmit' =>' $(window).unbind("beforeunload")' ,'id'=> 'saveMassInvoice','method' => 'POST']) !!}
          {{ csrf_field() }}

          {!! Form::hidden('data', json_encode($data,true)) !!}

          {{Form::submit('שמור',  ['id'=> 'save','name' => 'save','class' => 'progressMI btn btn-primary'])}}
          {{Form::submit('שלח Pdf',  ['id'=> 'save','name' => 'send','class' => 'progressMI btn btn-primary'])}}
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
  <div class="row">







  {{-- <div class="col-md-6 dropdown">
    {!! Form::open(['action'=> 'ordersController@pdfSend', 'method' => 'POST']) !!}
    {{ csrf_field() }}

    {!! Form::hidden('pdf', json_encode($data,TRUE)) !!}
    <button class="btn btn-primary dropdown-toggle"  type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Send Order
    </button>
    <div class="dropdown-menu ddpad" aria-labelledby="dropdownMenuButton">
      @foreach($data['suppliers'] as $supplier)
      {{ Form::checkbox($supplier->id, $supplier->name,['class' => 'menu-item'])}}
      {{ Form::label($supplier->id, $supplier->name, ['class' => 'menu-item']) }}<br>

      @endforeach

      <hr>
      {{Form::submit('Send Pdf',  ['class' => ' w-100  btn btn-primary'])}}
    </div>

  {!! Form::close() !!}
</div> --}}


</div>
<hr>

{{-- <div class="border 1px col-md-2" >Date: {{ $data['date'] }}</div> --}}
<br>


{{-- shabbos table --}}
<div class="row">

        <div class="col-12">
      <object  data="{{asset("storage/pdfInvoices/pdfMassInvoicePreview.pdf")}}" type="application/pdf" width="100%" height="500">
        <iframe src="{{asset("storage/pdfInvoices/pdfMassInvoicePreview.pdf")}}" width="100%" height="600"></iframe>
      </object>
      </div>
      </div>
<div id="modalbar" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Creating invoices</h5>

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
