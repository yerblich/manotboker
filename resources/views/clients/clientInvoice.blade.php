@extends('layouts.main')

@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{url('/invoices')}}">חשבוניות</a>
      {{-- {{$data['message']}} --}}
    </li>
    <li class="breadcrumb-item">
        <a href="#">חשבון - {{$invoice->id}}</a>
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
  
 

      <div class="col-1 " >
          {!! Form::open(['action'=> ['InvoiceController@destroy',$invoice->id ], 'method' => 'POST']) !!} 
          @method('DELETE')
          {{ csrf_field() }}
          {{Form::submit('הסר',  ['class' => 'deleteAlert btn btn-primary'])}}
          {!! Form::close() !!}
        </div>
    <div class="col-md-2 text-left" >
  {!! Form::open(['action'=> 'InvoiceController@pdfSend', 'method' => 'POST']) !!} 
  {{ csrf_field() }}
  {!! Form::hidden('client_id',$client->id) !!}
  {!! Form::hidden('from_date',$from_date) !!}
  {!! Form::hidden('to_date',$to_date) !!}
  {!! Form::hidden('invoice_id',$invoice->id) !!}
  {{Form::submit('שלח PDF',  ['class' => 'btn btn-primary'])}}
  {!! Form::close() !!}
</div>

<div class="col-md-3 text-center">
 @if ($paid >= $debt)
 <div class="badge badge-pill badge-success"> שולם </div>
 @else
    @if($paid == 0)
    <div class="badge badge-pill badge-danger">לא שולם </div>
    @else
    <div class="badge badge-pill badge-secondary"> שולם חלקית </div>
    @endif
 @endif
 
  @if($sent == 1)
  <div class="badge badge-pill badge-success">נשלח</div>
  @else
  <div class="badge badge-pill badge-danger ">לא נשלח </div>

  @endif
</div>
<div  class="col-md-6 text-right"> {!! Form::open(['action'=>[ 'InvoiceController@update',  $invoice->id ],'method' => 'POST']) !!} 
    {{ csrf_field() }}
    
    {!! Form::hidden('client_id',$client->id) !!}
  {!! Form::hidden('from_date',$from_date) !!}
  {!! Form::hidden('to_date',$to_date) !!}
    {!! Form::input('number','amountPaid', $invoice->paid,['step'=>'0.1', 'onkeypress'=> "return isNumberKey(event)",]) !!}
    {{Form::submit(' ביצוע תשלום',  ['name' => 'paid','class' => 'btn btn-primary'])}}
    @method('PUT')
    {!! Form::close() !!}</div>
</div>

<hr>

<div class="row">
  
    <div class="col-12">
  <object  data="{{asset("storage/pdfInvoices/".$client->name."/invoice".$invoice->id.".pdf")}}" type="application/pdf" width="100%" height="500">
    <iframe src="{{asset("storage/pdfInvoices/".$client->name."/invoice".$invoice->id.".pdf")}}" width="100%" height="600"></iframe>
  </object>
  </div>
  </div>
      @endsection
      @section('js')
     
      <script src={{ asset('js/deleteAlert.js') }}></script>
     
     
  
   
    @stop