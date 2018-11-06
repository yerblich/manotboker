@extends('layouts.main')
@section('content')
<div class=" addSupplier text-right dropdown-toggle btn btn-primary">הוסף ספק</div>
<div class="float-right">{!! Form::open(['action'=> 'ClientsController@store', 'method' => 'POST']) !!}
    {{ csrf_field() }}
    {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}


    {!! Form::text('Search','',[ 'class' => ' liveFilter'] ) !!}
    <div class="btn btn-primary">  חפש ספקים </div>
    {!! Form::close() !!}
</div>
<div style = "overflow:hidden; display:none" class="col-md-12  addSupplierDiv" >
    <br>
        {!! Form::open(['action'=> 'SuppliersController@store', 'method' => 'POST']) !!}
        {{ csrf_field() }}
        {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}
        {!! Form::text('supplierName','',['placeholder' => ' שם','class' => 'text-right form-control','required' => 'required'] ) !!}
        <br>
        {!! Form::text('email','',['placeholder' => ' מייל','class' => 'text-right form-control','required' => 'required'] ) !!}
        <br>
        {!! Form::text('number','',['placeholder' => ' טל','class' => 'text-right form-control'] ) !!}
        <br>
       {!! Form::text('address','',['placeholder' => ' כתובת','class' => 'text-right form-control'] ) !!}
       <br>
       {!! Form::text('city','',['placeholder' => ' עיר','class' => 'text-right form-control'] ) !!}
       <br>
        {{Form::submit('הוסף ספק', ['class' => 'btn btn-primary'])}}
        {!! Form::close() !!}
        <br>
      </div>


      <div class="row centerTable border">
          <table class="table table-hover liveFilterTable" id="dataTable"  width=80% cellspacing="0">
            <thead class="thead-light">
              <tr>


        </tr>
        </thead>

        <tbody>
            @foreach($suppliers as $supplier)
      <tr >
      <th class="text-right" scope="row">
          <a href="{{url('/suppliers')}}/{{$supplier->id }}">
        <div style="height:100%;width:100%">
            {{$supplier->name}}
        </div> </a>
      </th>

      </tr>
        @endforeach


        </tbody>
        <tfoot>
          <tr>

          </tr>
        </tfoot>
        </table>

        </div>
        <div class="row">
        <div class="paginateLinks"> {{ $suppliers->links() }}</div>
        </div>
@endsection
@section('js')
<script src={{ asset('storage/js/liveFilter.js') }}></script>


@stop
