@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">חשבוניות</a>
    </li>

  </ol>
  <div class="row">
  <div class="col-md-9" >
      {{-- {!! Form::open(['action'=> 'ordersController@pdfSend', 'method' => 'POST']) !!}
      {{ csrf_field() }}

      {!! Form::text('Search','',[ 'class' => ' liveFilter'] ) !!}
   <div class="btn btn-primary">  חפש לקוחות </div>

      {!! Form::close() !!} --}}
    </div>
  <div class=" col-md-3 float-rigth">
  <a class="" href="{{ url('invoice/MassInvoice')}}">
    <button type="button" class="btn btn-primary">ליצור חשבוניות מרובות</button>
  </a>
  </div>
</div>

  @if(count($invoices) > 0 )

    @foreach($invoices as $name => $invoiceArray)
<div class=" text-primary  invoiceGroup"><a  href="#">  {{$name}}</a></div>
<div  style="display:none" class=" well invoiceList">
  @foreach($invoiceArray as $invoice)
  <div class="invoiceGroupItem" >
     <a  href="{{url('/invoices')}}/{{$invoice->id}}"> {{$invoice->from_date }} To  {{$invoice->to_date }} </a>
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
