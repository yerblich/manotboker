@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">חשבוניות</a>
    </li>

  </ol>
  <div class="row">
  <div class="col-md-2" >
    <a class="" href="{{ url('invoices/info/allClientDebts')}}">
      <button type="button" class="btn btn-primary">הדפס מצב חובות</button>
    </a>
  </div>

    <div class="col-md-3">
  {!! Form::open(['action'=> ['InvoiceController@printInvoiceSummary'] ,'method' => 'POST']) !!}
  {{ csrf_field() }}
  {!! Form::text('from_date',null,['placeholder'=>'מתאריך','class' => ' form-control datepicker ', 'autocomplete' => 'off'] ) !!}

  </div>
  <div class="col-md-3">
    {!! Form::text('to_date',null,['placeholder'=>'לתאריך','class' => ' form-control datepicker ', 'autocomplete' => 'off'] ) !!}

</div>
<div class="col-md-2">
{{Form::submit(' הדפס סיכום חשבוניות', ['class' => 'deleteAlert btn btn-primary'])}}
{!! Form::close() !!}
</div>

  <div class=" col-md-2 ">
  <a class="float-right" href="{{ url('invoice/MassInvoice')}}">
    <button  type="button" class=" btn btn-primary">ליצור חשבוניות מרובות</button>
  </a>
  </div>
</div>
<br>
<hr>

  @if(count($invoices) > 0 )

    @foreach($invoices as $name => $invoiceArray)
<div class=" text-primary  invoiceGroup"><a  href="#">  {{$name}}</a></div>
<div  style="display:none" class=" well invoiceList">
  @foreach($invoiceArray as $invoice)
  <div class="invoiceGroupItem" >
     <a  href="{{url('/invoices')}}/{{$invoice->id}}"> {{$invoice->from_date->format('d-m-Y') }} To  {{$invoice->to_date->format('d-m-Y') }} </a>
  </div>

    @endforeach



</div>

    {{-- <div class="well">
    @if($invoice->debt - $invoice->paid  > 0 )
    <a class="text-danger" href="/invoices/{{$invoice->id}}"> Date :  {{$invoice->from_date }} To  {{$invoice->to_date }} </a>
    @else
    @if($invoice->debt - $invoice->paid  < 0 )
    <a class = "text-primary" href="/invoices/{{$invoice->id}}"> Date :  {{$invoice->from_date }} To  {{$invoice->to_date }} </a>
      @else
    <a class = "text-success" href="/invoices/{{$invoice->id}}"> Date :  {{$invoice->from_date }} To  {{$invoice->to_date }} </a>
    @endif
    @endif
    </div> --}}
    @endforeach

  @else
    <p> No Invoices </p>
  @endif





      @endsection
