@extends('layouts.main')
@section('content')

<div class="row">
{!! Form::open(['action'=> ['InvoiceController@generateMassInvoice'],'id'=> 'generateForm' ,'method' => 'POST']) !!}
{{ csrf_field() }}
<div class="col-md-3">
    <button class="btn btn-primary dropdown-toggle"  type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    בחר לקוחות
  </button>
  <div style="" class="dropdown-menu ddpad" aria-labelledby="dropdownMenuButton">
    {{ Form::radio('typeOfDocument', 'receipt' , true) }}  קבלה
    <br>
      {{ Form::radio('typeOfDocument', 'invoice' , false) }}  חשבונית
      <hr>

    {{ Form::checkbox('checkAll' ,'Clear/Fill','checked',['id' => 'checkAll','class' => 'checkAll menu-item'])}}
    {{ Form::label('checkAll', 'Clear/Fill', ['class' => 'menu-item']) }}<br>

<hr>
    @foreach($data['clients'] as $client)
    {{ Form::checkbox($client->id, $client->name,['class' => 'menu-item'])}}

    {{ Form::label($client->id, $client->name, ['class' => 'menu-item']) }}<br>

    @endforeach





  </div>
</div>
</div>
<br>
    <div class="row">
        <div class="col-md-5">
            {!! Form::text('from_date',null,['placeholder'=>'מתאריך','class' => ' form-control datepicker ', 'autocomplete' => 'off'] ) !!}
        </div>
        <div class="col-md-5">
       {!! Form::text('to_date',null,['placeholder'=>'לתאריך','class' => ' form-control datepicker ', 'autocomplete' => 'off'] ) !!}
    </div>
    <div class="col-md-2">
{{-- id="modalpress" onclick="return false" --}}
            <button  class=" btn btn-primary  ">ליצור חשבוניות מרובות</button>

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
{!! Form::close() !!}

</div>

@endsection
@section('overwriteAlert')

    <script>
        var token = '{{Session::token()}}';
        var url = '{{ route("checkExistingInvoice")}}'
    </script>
    <script src={{ asset('storage/js/checkAll.js') }}></script>
<script src={{ asset('storage/js/invoiceCheck.js') }}></script>
@stop
