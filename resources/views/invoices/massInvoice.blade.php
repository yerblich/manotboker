@extends('layouts.main')
@section('content')

<div class="row">
{!! Form::open(['action'=> ['InvoiceController@generateMassInvoice'],'id'=> 'generateForm' ,'method' => 'POST']) !!}
{{ csrf_field() }}
<div class="col-md-3">
    <button class="btn btn-primary dropdown-toggle"  type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    בחר לקוחות
  </button>
  <div style="width:max-content; margin-top: 47px;height: 500px;overflow-y: scroll;" class="dropdown-menu ddpad" aria-labelledby="dropdownMenuButton">
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

            <button id="modalpress" onclick="return false" class=" btn btn-primary ">ליצור חשבוניות מרובות</button>

    </div>
    <div id="modalbar" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">hello</h5>

                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                        The following have duplicate invoicess
                        </div>
                            <div class="col-md-2">
                                    <button id="cancel" onclick="return false" class=" btn btn-danger ">Cancel</button>
                                    </div>
                        <div class="col-md-4">

                        </div>

                    </div>
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
<script src={{ asset('js/invoiceCheck.js') }}></script>
@stop
