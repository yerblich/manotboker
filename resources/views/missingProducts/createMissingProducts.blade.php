@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <style>

    .headcol {
      text-align: center;
      height: 49px;
    position: absolute;
    width: 15.3em;
    margin-left: -219px;
    background-color: white;
    z-index: 1;
    }
  </style>

  <ol class="breadcrumb">
      <li class="breadcrumb-item">
          <a href="{{ url('/suppliers')}}">ספקים</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ url('/suppliers')}}/{{$data['supplier']->id}}">{{$data['supplier']->name}}</a>
          </li>
    <li class="breadcrumb-item">
      <a href="#">מוצרים חסרים</a>
    </li>

  </ol>
  {{-- @if( !count($data['products']))
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



@else --}}





  {!! Form::open(['action'=> 'MissingProductsController@store', 'method' => 'POST'],['class' => 'form-group']) !!}
{{ Form::hidden('supplier_id',$data['supplier']->id) }}
{{ Form::hidden('date',$data['date']) }}
  <h2>תאריך: {{$data['date']}} </h2>
<hr>

{{-- create table for each type of product i.e shabbos and daily  --}}
 @foreach($data['products'] as $orderType => $products)
<h1>{{ ucfirst($orderType)}}</h1>
 <div class=" blueScroll table-responsive form-group">
  <table class=" sticky table table-bordered table-responsive" id="dataTable" width="100%" cellspacing="0">
    <thead class="thead-light">
      <tr>


          {{-- itirate through alll products and create header  --}}
  @if(count( $products) > 0 )

    @foreach($products as $id => $name)


        <th>{{$name}}</th>

    @endforeach

  @else
    <p> לא נמצאו מוצרים </p>
  @endif
</tr>
</thead>
<tfoot>


      {{-- table footer --}}
      @foreach($products as $id => $name)


      <th>{{$name}}</th>


    @endforeach
  </tr>
</tfoot>
<tbody>



   <tr>



      @foreach($products as $id => $name)

      <td>{{Form::input('number',$id, null,['onkeypress'=> "return isNumberKey(event)",'class' => '  form-control col-12' ,'style' => 'padding:1px; text-align:center'])}} </td>

      @endforeach





    </tr>





</tbody>
</table>
</div>

 @endforeach

{{Form::submit('Submit', ['id'=>"progress"])}}
{!! Form::close() !!}

<div id="modalbar" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">יצירת דוח</h5>

      </div>
      <div class="modal-body">

        <div style="display:none" id="progressbar"></div>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>

 {{-- @endif --}}
      @endsection
      @section('js')

        <script src={{ asset('storage/js/isNumberKey.js') }}></script>




      @stop
