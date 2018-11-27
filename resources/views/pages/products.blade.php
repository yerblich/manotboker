@extends('layouts.main')
@section('content')
@if(!empty($suppliers))
<div class=" addProduct text-right dropdown-toggle btn btn-primary">הוסף מוצר </div>
<div class="float-right">{!! Form::open(['action'=> 'ProductsController@search', 'method' => 'POST']) !!}
{{ csrf_field() }}
{{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}
{!! Form::text('Search','',[ 'class' => ' liveFilter'] ) !!}
<div class="btn btn-primary"> חפש מוצרים </div>
{!! Form::close() !!}</div>
<div  style = "overflow:hidden; display:none"class="well   col-md-12 addProductDiv " >
   <div class="mt-2">
        {!! Form::open(['action'=> 'ProductsController@store', 'method' => 'POST']) !!}
        {{ csrf_field() }}


        <div class=" form-group input-group   ">
          {!! Form::text('productName','',['placeholder' => 'שם','class' => 'text-right form-control' ,'required' => 'required'] ) !!}

            <div class="input-group-append">
                <span class="input-group-text">שם</span>
              </div>
        </div>
        <div class=" form-group input-group   ">
          {!! Form::text('weight','',['placeholder' => 'משקל','class' => 'text-right form-control'] ) !!}

            <div class="input-group-append">
                <span class="input-group-text">משקל</span>
              </div>
        </div>
        <div class=" form-group input-group   ">
          {!! Form::text('units','',['placeholder' => 'כמות באריזה','class' => 'text-right form-control'] ) !!}

            <div class="input-group-append">
                <span class="input-group-text">כמות באריזה</span>
              </div>
        </div>
        <div class=" form-group input-group   ">
            {!! Form::input('number','price','',['step'=>"0.01",'onkeypress'=> "return isNumberKey(event)",'placeholder' => 'מחיר','class' => 'text-right form-control'] ) !!}

            <div class="input-group-append">
                <span class="input-group-text">&#8362;</span>
              </div>
        </div>

        <div class=" form-group input-group   ">
           {!! Form::select('type',[
                 0 => 'שבת' ,
                 1 => 'יומי',
                 2 => 'אמריקאי']
           ,null,['class' => ' text-right custom-select'] ) !!}

            <div class="input-group-append">
                <span class="input-group-text">סוג</span>
              </div>
        </div>

        <div class=" form-group input-group   ">
           {!! Form::select('supplier', $suppliers,null,['class' => 'text-right custom-select']) !!}

            <div class="input-group-append">
                <span class="input-group-text">ספק</span>
              </div>
        </div>


        {{Form::submit('הוסף מוצר', ['class' => 'float-right btn btn-primary'])}}
        {!! Form::close() !!}
      </div>
    </div>
<br>
      <div class="row centerTable border">
        <table class="table table-hover liveFilterTable" id="dataTable"  width=80% cellspacing="0">
          <thead class="thead-light">
            <tr>


      </tr>
      </thead>

      <tbody>
        @foreach($products as $product)

        <tr >

      <th class="text-right" scope="row">
        <a href="{{url("/products/$product->id") }}">
          <div style="height:100%;width:100%">
          {{$product->name}}
          </div>
       </a>
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
      @else
      <div class="text-right">
          <a class="btn btn-primary" href="{{ url('/suppliers')}}"><span class="nav-link-text">ספקים</span>
          </a>
        לא נמצא ספקים ,נא להוסיף ספק

      </div>

      @endif
      {{-- {!! Form::open(['route'=> ['products.destroy',$product->id], 'method' => 'DELETE']) !!}
      {{ csrf_field() }}
    <th scope="row">  </th>
    <td>{{Form::submit('Delete Product', ['class' => 'btn btn-primary btn-sm'])}}</td>

  </tr>
  {!! Form::close() !!}  --}}

@endsection
@section('js')

  <script src={{ asset('storage/js/isNumberKey.js') }}></script>

  <script src={{ asset('storage/js/liveFilter.js') }}></script>


@stop
