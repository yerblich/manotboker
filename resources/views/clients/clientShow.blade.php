@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{ url('/clients')}}">לקוחות</a>
    </li>
    <li class="breadcrumb-item">
        <a href="#">{{$data['client']->name}}</a>
      </li>
  </ol>
  {{-- <div class="row">
  <div class="col-md-4 text-center" >
      {!! Form::open(['action'=> 'ordersController@pdfDownload', 'method' => 'POST']) !!}
      {{ csrf_field() }}

      {!! Form::hidden('pdf', json_encode($data,TRUE)) !!}
      {{Form::submit('Download Pdf', ['class' => 'btn btn-primary'])}}
      {!! Form::close() !!}
    </div>

       <div class="col-md-4 text-center" >
  {!! Form::open(['action'=> 'ordersController@pdfSave', 'method' => 'POST']) !!}
  {{ csrf_field() }}

  {!! Form::hidden('pdf', json_encode($data,TRUE)) !!}
  {{Form::submit('Save Pdf',  ['class' => 'btn btn-primary'])}}
  {!! Form::close() !!}
</div>
  <div class="col-md-4 text-center" >
  {!! Form::open(['action'=> 'ordersController@pdfSend', 'method' => 'POST']) !!}
  {{ csrf_field() }}

  {!! Form::hidden('pdf', json_encode($data,TRUE)) !!}
  {{Form::submit('Send Pdf',  ['class' => 'btn btn-primary'])}}
  {!! Form::close() !!}
</div>

</div>
<hr>
<div class="col-md-3 float-right" >
    {!! Form::open(['action'=> 'ordersController@pdfSend', 'method' => 'POST']) !!}
    {{ csrf_field() }}

    {!! Form::text('date',null,['class' => 'datepicker ', 'autocomplete' => 'off'] ) !!}
    {{Form::submit('Search',  ['class' => 'datepicker btn btn-primary'])}}
    {!! Form::close() !!}
  </div>
     --}}
{{-- <div class="border 1px col-md-2" >Date: {{ $data['date'] }}</div> --}}
<div class="  col-md-3 "><a class="btn btn-primary" href="{{ url('/clients')}}/{{$data['client']->id}}/edit">
  עֲרוֹך</a> </div>
<br>





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
{!! Form::open(['action'=>['ClientsController@search', $data['client']->id] , 'method' => 'POST']) !!}
{{ csrf_field() }}
{{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}
<div class="row ">
    <div class="col-5">
{!! Form::text('from_date',null,['placeholder' => 'From', 'class' => 'form-control datepicker ', 'autocomplete' => 'off'] ) !!}
    </div>
    <div class="col-5">
{!! Form::text('to_date',null,['placeholder' => 'To', 'class' => ' form-control datepicker ', 'autocomplete' => 'off'] ) !!}

</div>
<div class="col-2">
  {{Form::submit('חיפוש  הזמנות',  ['class' => 'form-control btn btn-primary'])}}
</div>

</div>
{!! Form::close() !!}





<br>
<div class="row">
<div class=" col-md-8 chartWrapper float-left">
<div id="stocks-chart"></div>
{!! \Lava::render('ColumnChart', 'Finances', 'stocks-chart');!!}
</div>

<div class="  col-md-3 float-right">
 <div class="text-center"> חשבוניות אחרונות<br></div>
 <div class="clientInvoices">
    @if(count($data['invoices']) > 0 )
  @foreach($data['invoices'] as $invoice)
    <div class="well">
    @if($invoice->debt - $invoice->paid > 0 )
    <a class="text-danger" href="{{ url('/invoices')}}/{{$invoice->id}}">   {{$invoice->from_date->format('d-m-Y') }} To  {{$invoice->to_date->format('d-m-Y') }} </a>
    @else
    @if($invoice->debt - $invoice->paid  < 0 )
    <a class = "text-primary" href="{{ url('/invoices')}}/{{$invoice->id}}">   {{$invoice->from_date->format('d-m-Y') }} To  {{$invoice->to_date->format('d-m-Y') }} </a>
      @else
    <a class = "text-success" href="{{ url('/invoices')}}/{{$invoice->id}}">   {{$invoice->from_date->format('d-m-Y') }} To  {{$invoice->to_date->format('d-m-Y') }} </a>
    @endif
    @endif
    </div>
    @endforeach

  @else
    <p> לא נמצאו חשבוניות </p>
  @endif






 </div>
</div>
</div>
{{-- <div>{!! $data['chart']->container() !!}</div> --}}

{{-- @if(!$data['orders']->all() == '')
  <div class="table-responsive">
  <table class="table table-bordered table-responsive" id="dataTable" width="100%" cellspacing="0">
    <thead class="thead-light">
      <tr>
        <th>Date</th>
        @if(count($data['products']) > 0 )

        @foreach($data['products'] as $product)


          <th>{{$product->name}}</th>
          <th>R</th>

        @endforeach

      @else
        <p> No Products </p>
      @endif
</tr>
</thead>

<tbody>



        @foreach($data['orders'] as $key => $order)
     <tr>
        <td>{{$order->date->format('d-m-Y')}}</td>

            @foreach($data['products'] as $product)

                    <td>{{$order[$product->name]}}</td>

          <td class="table-danger">{{$order->return[$product->name]}}</td>

                    @endforeach
    </tr>
        @endforeach





</tbody>
<tfoot>
  <tr >
      <th class="table-success">Total</th>




             @foreach($data['products'] as $product)

                     <td class="table-success">{{$data['orderTotals'][$product->name]}}</td>

           <td class="table-danger">{{$data['returnTotals'][$product->name]}}</td>

                     @endforeach


  </tr>
  <tr>
        <th>Date</th>

    @if(count($data['products']) > 0 )

    @foreach($data['products'] as $product)


      <th>{{$product->name}}</th>
      <th>R</th>


    @endforeach

  @else
    <p> No Products </p>
  @endif
  </tr>
</tfoot>
</table>
</div>
@else
<br>
No orders
@endif --}}


      @endsection
