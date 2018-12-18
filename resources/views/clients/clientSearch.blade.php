@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
      <li class="breadcrumb-item">
          <a href="{{ url('/clients')}}">לקוחות</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ url('/clients')}}/{{$data['client']->id}}">{{$data['client']->name}}</a>
          </li>

  </ol>
  <div class="row">
  <div class="col-md-3 text-center" >
      {{-- {!! Form::open(['action'=> ['ClientsController@pdfDownload', $data['client']->id], 'method' => 'POST']) !!}
      {{ csrf_field() }}

      {!! Form::hidden('pdf', json_encode($data,TRUE)) !!}
      {{Form::submit('הורד PDF', ['class' => 'btn btn-primary'])}}
      {!! Form::close() !!} --}}
    </div>


  <div class="col-md-3 text-center" >
  {{-- {!! Form::open(['action'=> ['ClientsController@pdfSend',$data['client']->id], 'method' => 'POST']) !!}
  {{ csrf_field() }}

  {!! Form::hidden('pdf', json_encode($data,TRUE)) !!}
  {{Form::submit('Send Pdf',  ['class' => 'btn btn-primary'])}}
  {!! Form::close() !!} --}}
</div>
<div class="col-md-6 text-center" >
  {!! Form::open(['action'=> ['ClientsController@search',$data['client']->id], 'method' => 'POST']) !!}
  {{ csrf_field() }}

  From {!! Form::text('from_date',$data['from_date'],['class' => 'datepicker ', 'autocomplete' => 'off'] ) !!}
         To {!! Form::text('to_date',$data['to_date'],['class' => 'datepicker ', 'autocomplete' => 'off'] ) !!}
         {{Form::submit('חפש',   ['name' => 'search','value'=>'search','class' => 'btn btn-primary'])}}

  {!! Form::close() !!}
</div>

</div>
<hr>
<div class="col-md-3 float-right" >

  </div>

{{-- <div class="border 1px col-md-2" >Date: {{ $data['date'] }}</div> --}}
<br>
<div class="row">
  <div class="col-md-4 ">
    שם לקוי:   {{$data['client']->name}}<br>
    מספר לקוח:  {{$data['client']->id}}
  </div>
   {{-- <div class="col-md-6">
      {!! Form::open(['action'=>['ClientsController@search', $data['client']->id] , 'method' => 'POST']) !!}
          {{ csrf_field() }}


         From {!! Form::text('from_date',$data['from_date'],['class' => 'datepicker ', 'autocomplete' => 'off'] ) !!}
         To {!! Form::text('to_date',$data['to_date'],['class' => 'datepicker ', 'autocomplete' => 'off'] ) !!}
         {{Form::submit('Search',  ['class' => 'btn btn-primary'])}}

          {!! Form::close() !!}
   </div> --}}
    <div class="col-md-8 text-right">
    {!! Form::open(['action'=>['InvoiceController@create', $data['client']->id], 'method' => 'POST',]) !!}
        {{ csrf_field() }}

         {{-- {!! Form::hidden('from_date',json_encode($data['from_date'],TRUE) ) !!}
         {!! Form::hidden('to_date',json_encode($data['to_date'],TRUE) ) !!} --}}
         {!! Form::hidden('data', json_encode($data,TRUE)) !!}

        {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}
       {{Form::submit('ליצור חשבונית',  ['name' => 'create','class' => 'progressBar  btn btn-primary'])}}
        {!! Form::close() !!}
 </div>


</div>

@if(!$data['orders']->all() == '')
  <div class="table-responsive">
  <table class="table table-bordered table-responsive" id="dataTable" width="100%" cellspacing="0">
    <thead class="thead-light">
      <tr>
        <th>Date</th>
        @if(count($data['productNames']) > 0 )

        @foreach($data['productNames'] as $productid => $name)


          <th>{{$name}}</th>
          <th>ח</th>

        @endforeach

      @else
        <p> לא נמצאו מוצרים </p>
      @endif
</tr>
</thead>

<tbody>



  @foreach($data['allOrders'] as $orderDate => $orderAndReturns)

     <tr>
      <td style="white-space:nowrap">{{$orderDate}}</td>


                      @foreach($orderAndReturns['orders'] as $productId => $qty)
                    <td>{{$qty}}</td>
                  @if(array_key_exists('returns', $orderAndReturns))
                    <td class="table-danger">{{$orderAndReturns['returns'][$productId]}}</td>

                    @else
                    <td class="table-danger">0</td>
                    @endif
                    @endforeach



    </tr>
    @endforeach





</tbody>
<tfoot>
  <tr >
      <th class="table-success">סה"כ</th>




             @foreach($data['productOrderTotals'] as $product)

                     <td class="table-success">{{$product['orders']}}</td>

  <td class="table-danger">{{$product['returns']}}</td>

                     @endforeach


  </tr>
  <tr>
        <th>Date</th>

    @if(count($data['productNames']) > 0 )

    @foreach($data['productNames'] as $productid => $name)


      <th>{{$name}}</th>
      <th>ח</th>


    @endforeach

  @else
    <p> לא נמצאו מוצרים </p>
  @endif
  </tr>
</tfoot>
</table>
</div>
@else
<br>
לא נמצאו הזמנות
@endif

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

<div class="row">

  <div class="col-12">
<object  data="{{asset("storage/pdfInvoices/detailedInvoice.pdf")}}" type="application/pdf" width="100%" height="500">
  <iframe src="{{asset("storage/pdfInvoices/detailedInvoice.pdf")}}" width="100%" height="600"></iframe>
</object>
</div>
</div>

      @endsection
      @section('overwriteAlert')

      @if($data['overWriteAlert'])
        <script src={{ asset('storage/js/overwriteAlert.js') }}></script>


     @else
       <script src={{ asset('storage/js/progressBar.js') }}></script>
     @endif

      @stop
