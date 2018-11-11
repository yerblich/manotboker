@extends('layouts.main')

@section('content')




  {!! Form::open(['action'=>'ClockerController@create' ,'method' => 'POST']) !!}
  {{ csrf_field() }}
  {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}

  <div class="row ">
    <div class="col-2">
      {{Form::submit('צור דוח',  ['class' => 'form-control btn btn-primary'])}}
    </div>

    <div class="col-3">
{!! Form::text('to_date',null,['placeholder' => 'לתאריך', 'class' => ' text-right form-control datepicker ', 'autocomplete' => 'off' ,'required' => 'required'] ) !!}

</div>
      <div class="col-3">
  {!! Form::text('from_date',null,['placeholder' => 'מתאריך', 'class' => 'text-right form-control datepicker ','autocomplete' => 'off', 'required' => 'required'] ) !!}
      </div>

      <div class="col-2">
      {!! Form::text('wage',null,['placeholder' => 'שכר', 'class' => 'text-right form-control  ', 'required' => 'required'] ) !!}
      </div>
  <div class="col-2">
{!! Form::text('employee',null,['placeholder' => 'עובד', 'class' => 'text-right form-control  ', 'required' => 'required'] ) !!}
  </div>


  </div>
  {!! Form::close() !!}



@endsection
