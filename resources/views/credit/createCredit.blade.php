
@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{ url('/credit')}}">זיכויים -  צור</a>
    </li>
    <li class="breadcrumb-item">
    <a href="#">{{$data['client']->name}}</a>
          </li>

  </ol>

</div>
  <div class="row">
<div class="col-md-10 form" style="margin:auto" >
    {!! Form::open(['action' => ['CreditController@store', 'client_id='.$data['client']->id  ]  ,'method' => 'POST']) !!}
    {!! csrf_field() !!}
    <div class="row" >
  <div class="col-md-11 text-center">
    {{ Form::radio('creditType' ,'example' )}}
    {{ Form::label('creditType', 'דוגמה',['style' => 'padding-right:2%']) }}


  {{ Form::radio('creditType' ,'credit' , true)}}
  {{ Form::label('creditType', 'חשבונית זיכוי', ['class' => 'form-fontrol']) }}



  </div>
  <hr>
    <table class="table">
      <thead>
        <tr>

          <th scope="col">סכום</th>
          <th scope="col">מחיר ליחידה</th>
          <th scope="col">תאור</th>
          <th scope="col">כמות</th>
            <th scope="col">#</th>
              <th scope="row"><i  style ="color:green"  class=" addMore fa fa-plus-circle"></i></th>
        </tr>
      </thead>
      <tbody>
        <tr>

          <td>{!! Form::text('credit_items[1][total]','',['placeholder' => 'סה״כ','class' => 'text-right form-control'] ) !!}</td>

          <td>{!! Form::text('credit_items[1][unit_price]','',['placeholder' => 'מחיר ליחידה','class' => 'text-right form-control'] ) !!}</td>

          <td>{!! Form::textarea('credit_items[1][description]','',['rows'=> 1,'placeholder' => 'תאור','class' => 'text-right form-control'] ) !!}</td>
          <td>{!! Form::text('credit_items[1][amount]','',['placeholder' => 'כמות','class' => 'text-right form-control'] ) !!}</td>
          <th scope="row">1</th>

        </tr>

      </tbody>
    </table>
    <div class="  col-md-1 ">{{Form::submit('שמור', ['class' => 'btn btn-primary'])}} </div>

     {!! Form::close() !!}
</div>

  </div>





      @endsection
      @section('js')

      <script src={{ asset('storage/js/addInputGroup.js') }}></script>




    @stop
