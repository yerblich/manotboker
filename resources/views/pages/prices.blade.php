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

  <div class="table-responsive blueScroll tableWrapper">
  <table class="table table-striped table-bordered table-responsive" id="dataTable" width="100%" cellspacing="0">
    <thead class="thead-light">
      <tr>
          <th class="headcol">לקוחות</th>
  @if(count($data) > 0 )

    @foreach($data['products'] as $product)


      <th>{{$product->name}}</th>


    @endforeach

  @else
    <p> לא נמצא מוצרים </p>
  @endif
</tr>
</thead>

<tbody>


  @foreach($data['allPrices'] as $client => $priceList)
    <tr>

        <td class="headcol">{{$client}}</td>
        @foreach($priceList as $product => $price)

        <td> {{Form::input('number',$client . "_" . $product,  $price,
          ['step'=>'0.1','onkeypress'=> "return isNumberKey(event)",'class' => 'form-control col-12' ,'style' => 'padding:1px; text-align:center'])}} </td>



         @endforeach

      </tr>

  @endforeach


</tbody>
<tfoot>
  <tr>
      <th class="headcol">Clients</th>
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
@endif
      @endsection
      @section('js')

        <script src={{ asset('storage/js/isNumberKey.js') }}></script>




      @stop
