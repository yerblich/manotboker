@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <style>
  .table
    {
      overflow: initial;
  /* margin-left:15em; */
    }
    .tableWrapper{
      height:400px;
    }
    thead th {
      position: sticky; top: 0; z-index: 100;
    }
  </style>
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">מחירים</a>
    </li>

  </ol>
  @if($data['clients']->count() == 0 &&  $data['products']->count() == 0  )
  <div class="text-right">
      <a class="btn btn-primary" href="{{ url('/clients')}}">
        <span class="nav-link-text">לקוחות</span>
      </a>
       לא נמצאו מוצרים, לחץ להוספה
    <br>
  </div>
  <br>
    <div class="text-right">
    <a class="btn btn-primary" href="{{ url('/products')}}">
      <span class="nav-link-text">מוצרים</span>
    </a>

    לא נמצאו לקוחות, לחץ להוספה
      </div>

  @elseif(!$data['clients']->count() )
  <div> לא נמצא לקוחות
      <a class="btn btn-primary" href="{{ url('/clients')}}">
        <span class="nav-link-text">Clients</span>
      </a>
  </div>

  @elseif( !$data['products']->count())
  <div> לא נמצא מוצרים
      <a class="btn btn-primary" href="{{ url('/products')}}">
        <span class="nav-link-text">Products</span>
      </a>

  </div>
@else

<hr>


<br>

{!! Form::open(['action'=> ['PricesController@store','1' ],'method' => 'POST']) !!}
{{ csrf_field() }}

  <div class=" table-responsive blueScroll tableWrapper">
  <table class="table table-striped table-bordered table-responsive" id="dataTable" width="100%" cellspacing="0">
    <thead class="thead-light">
      <tr>
          <th class="headcol">לקוחות</th>
          
  @if(count($data) > 0 )

    @foreach($data['products'] as $product)


      <th>{{$product->name}}</th>


    @endforeach


  @endif
</tr>
<tr>

  <td class="fillBg">Fill</td>
  @foreach($data['products'] as $product)


    <td class="fillBg"> {{Form::input('number',$product->id ,null ,
      ['step'=>'0.01','onkeypress'=> "return isNumberKey(event)",'class' => $product->id. ' fill form-control col-12' ,'style' => 'padding:1px; text-align:center'])}}
    </td>

@endforeach
</tr>
</thead>

<tbody>


  @foreach($data['allPrices'] as $client => $priceList)

    <tr>

        <td class="headcol">{{$client}}</td>
        {{-- <td class="fillBg"> {{Form::input('number',$client ,null ,
          ['step'=>'0.01','onkeypress'=> "return isNumberKey(event)",'class' => $client. ' fill form-control col-12' ,'style' => 'padding:1px; text-align:center'])}} </td> --}}

        @foreach($priceList as $product => $price)

        <td > {{Form::input('number',$client . "_" . $product,  $price,
          ['step'=>'0.01','onkeypress'=> "return isNumberKey(event)",'class' => $client. ' form-control col-12 ' . $product ,'style' => 'padding:1px; text-align:center'])}} </td>



         @endforeach

      </tr>

  @endforeach


</tbody>
<tfoot>
  <tr>
      <th class="headcol">Clients</th>
      {{-- <th class="headcol">Fill      </th> --}}
      @foreach($data['products'] as $product)


      <th>{{$product->name}}</th>


    @endforeach
  </tr>
</tfoot>
</table>
</div>
<br/>
{{Form::submit('עדכון', ['class' => 'btn btn-primary'])}}
{!! Form::close() !!}
<div id="form" class=" table-responsive blueScroll tableWrapper"> </div>
@endif
      @endsection
      @section('js')
        <script>
        var data = @json($data);
      var action =   '{{action('PricesController@store','1')}}'
        </script>

        <script src={{ asset('storage/js/isNumberKey.js') }}></script>
{{-- <script src={{ asset('storage/js/inputs.js') }}></script> --}}
<script src={{ asset('storage/js/fill.js') }}></script>


      @stop
