@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{url('/suppliers')}}">ספקים</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{url('/suppliers')}}/{{$data['supplier']->id}}">{{$data['supplier']->name}}</a>
      </li>

  </ol>

  <div class="row">



              <div class="  col-md-1 "> <a class="btn btn-primary" href="{{url('/suppliers')}}/{{$data['supplier']->id}}/edit">
            עֲרוֹך</a> </div>

  </div>
  <br>
  <div class="card clientInfo ">
    <div class="row justify-content-end p-4">

      <div class="col-md-4  text-right">
        <h2>כתובת </h2>
        <ul style = "direction:rtl;" class="list-group">
            עיר: {{$data['supplier']->city}}<br>
            רחוב: {{$data['supplier']->address}}<br>

        </ul>
        </div>
      <div class="col-md-5  text-right">
            <h2>אנשי קשר</h2>
            <ul style = "direction:rtl;" class="list-group">
                מייל: {{$data['supplier']->email}}<br>
                טל: {{$data['supplier']->number}}<br>
            </ul>
          </div>

          <div class="col-md-3 text-right ">
              <h2>פרטים </h2>
              <ul style = "direction:rtl;" class="list-group-flush">
                 שם: {{$data['supplier']->name}}<br>
                 מספר ספק:  {{$data['supplier']->id}}
              </ul>
            </div>
        </div>
  </div>
  <br>
<div class="row">
  <div class="col-md-8  ">

    {{-- debt to supplier --}}
    <div class="card text-center">
        <div class="card-header">
          <div class="row">
          <div class="col-md-3 text-left">
            <a href="{{ route('reports.index',[ 'supplierId' => $data['supplier']->id])}}" class=" btn btn-primary"> דוחות</a>
          </div>
           <div class="col-md-6 text-center">
             <strong class = "text-right"style="font-size:152%">מאזן שוטף </strong>
            </div>

           <div class="col-md-3 text-right">
              <a href="{{ route('missingProducts.index',[ 'supplierId' => $data['supplier']->id])}}" class=" btn btn-primary"> מוצרים חסרים</a>
           </div>
          </div>
        </div>
        <div class="card-body">
        <h3 class="card-title "> <strong>&#8362;{{$data['totalDebtToSupplier']}}  -  &#8362; {{$data['totalMissingCost']}}</strong></h3>
        <p class="card-text  text-danger"><strong><h2 class="text-danger">Total: &#8362;{{$data['totalDebtToSupplier'] - $data['totalMissingCost']}}</h2></strong></p>
        {!! Form::open(['action'=> ['SuppliersController@missingProductsReport',$data['supplier']->id ], 'method' => 'POST']) !!}
        {!! Form::hidden('from_date',$data['from_date'],['placeholder' => 'From'] ) !!}
        {!! Form::hidden('to_date',$data['to_date'],['placeholder' => 'to'] ) !!}
        {{ csrf_field() }}
        {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}

        {{Form::submit('שלח דו"ח לספק', ['class' => 'btn btn-primary'])}}
        {!! Form::close() !!}
        {{-- <a href="/suppliers/{{$data['supplier']->id}}/report/{{$data['month']}}" class="btn btn-primary">שלח דו"ח לספק</a> --}}
        </div>
        <div class="card-footer text-muted">

            {!! Form::open(['action'=>['SuppliersController@show', $data['supplier']->id] , 'method' => 'POST']) !!}
            {{ csrf_field() }}
            {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}
            <div class="row ">
                <div class="col-4">
            {!! Form::text('from_date',$data['from_date'],['placeholder' => 'From','id' => 'from_date', 'class' => 'updateDates form-control datepicker ', 'autocomplete' => 'off'] ) !!}
                </div>
                <div class="col-4">
            {!! Form::text('to_date',$data['to_date'],['placeholder' => 'To','id' => 'to_date', 'class' => 'updateDates form-control datepicker ', 'autocomplete' => 'off'] ) !!}

            </div>
            <div class="col-4">
              {{Form::submit('Go',  ['class' => 'form-control btn btn-primary'])}}
            </div>

            </div>
            {!! Form::close() !!}
            </div>
      </div>
    </div>
    <div class="col-md-4 text-center  ">

        <li class="list-group-item active"> מוצרים</li>


      <ul  class=" supplierProducts list-group">
        @foreach($data['products'] as $product)
        <div class="float-right  text-right"> <li class="list-group-item">
            <a href="{{url('/products')}}/{{$product->id }}">
              <div style="height:100%;width:100%">
              {{$product->name}}
              </div>
           </a>
        </li></div>

        @endforeach
      </ul>
       </div>





   </div>
    <br/>




      @endsection
      @section('js')

      <script src={{ asset('js/updateDates.js') }}></script>




    @stop
