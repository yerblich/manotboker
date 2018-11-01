@extends('layouts.main')
@section('content')
<br>


<div class="addClient text-right dropdown-toggle btn btn-primary">הוסף לקוח</div>
<div class="float-right">
    {!! Form::open(['action'=> 'ClientsController@store', 'method' => 'POST']) !!}
    {{ csrf_field() }}
    {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}

    {!! Form::text('Search','',[ 'class' => ' liveFilter'] ) !!}
   <div class="btn btn-primary">  חפש לקוחות </div>

    {!! Form::close() !!}
  </div>
<div  style = "overflow:hidden; display:none" class=" col-md-12  addClientDiv" >
        {!! Form::open(['action'=> 'ClientsController@store', 'method' => 'POST']) !!}
        {{ csrf_field() }}
        {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}
        <br>
        {!! Form::text('clientName','',['placeholder' => 'שם', 'class' => ' text-right form-control'] ) !!}<br>
        {!! Form::text('email',null,['placeholder' => 'מייל','class' => 'text-right form-control'] ) !!}<br>
        {!! Form::text('city',null,['placeholder' => 'עיר','class' => 'text-right form-control'] ) !!}<br>
       {!! Form::text('address',null,['placeholder' => 'רחוב','class' => 'text-right form-control'] ) !!}<br>
       {!! Form::text('number',null,['placeholder' => 'טל','class' => 'text-right form-control'] ) !!}<br>
        {{Form::submit('הוסף לקוח', ['class' => 'btn btn-primary'])}}
        {!! Form::close() !!}
      </div>

<br>
      <div class="row centerTable border">
        <table class="table table-hover liveFilterTable" id="dataTable"  width=80% cellspacing="0">
          <thead class="thead-light">
            <tr>


      </tr>
      </thead>

      <tbody>
        @foreach($clients as $client)
    <tr >
    <th class="text-right" scope="row">
        <a href="{{url("/clients")}}/{{$client->id }}">
      <div style="height:100%;width:100%">
        {{$client->name}}
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
      {{-- <div class="row">
      <div class="paginateLinks"> {{ $clients->links() }}</div>
      </div> --}}
@endsection
@section('js')
<script src={{ asset('js/liveFilter.js') }}></script>


@stop
