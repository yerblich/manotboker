@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{ url('/products')}}">מוצרים</a>
    </li>
    <li class="breadcrumb-item">
    <a href="#">{{$data['name']}}</a>
          </li>

  </ol>
  <div class="row">

        <div class="  col-md-3 "><a class="btn btn-primary" href="{{url("products/")}}/{{$data['id']}}/edit">
            עֲרוֹך</a> </div>
  </div>
<div class="row">
        <div class="float-right col-md-2 text-right ">

        </div>
        <div class="float-right col-md-4 text-center ">
            <div class="card">
                <div class="card-body">
                    @if($data['imageName'] == 'noimage.jpg')
                    <img  width = "90%"class="img  " src="{{asset("/storage/productImages/")}}/{{$data["imageName"]}}">
               @else
                     <img  width = "90%"class="img  " src="{{asset("/storage/productImages/")}}/{{$data['supplier']}}/{{$data['imageName']}}">
                 @endif
                </div>
              </div>

        </div>
        <div class="float-left col-md-3 text-left form-group">

            <div class=" form-group float-right  text-right"><b>ספק</b>   {!! Form::text('supplier',$data['supplier'],['placeholder' => 'ספק ', 'class' => 'form-control text-right', 'readonly'] ) !!}    </div>
            <div class=" form-group float-right  text-right"><b>מספר מוצר</b>   {!! Form::text('productId',$data['id'],['placeholder' => 'מספר מוצר ', 'class' => 'form-control text-right', 'readonly'] ) !!}    </div><br>
            <div class=" form-group float-right  text-right"><b>שם מוצר</b>   {!! Form::text('productName',$data['name'],['placeholder' => 'שם מוצר ', 'class' => 'form-control text-right', 'readonly'] ) !!}    </div><br>
            <div class=" form-group float-right  text-right"> <b>משקל</b>  {!! Form::text('weight',$data['weight'],['placeholder' => 'משקל ', 'class' => 'form-control text-right', 'readonly'] ) !!}    </div><br>
            <div class=" form-group float-right  text-right"> <b>מחיר</b>  {!! Form::text('supplierPrice',$data['supplierPrice'],['placeholder' => 'מחיר ', 'class' => 'form-control text-right', 'readonly'] ) !!}    </div><br>
            <div class=" form-group float-right  text-right"> <b>סוג</b>  {!! Form::text('type',$data['type'],['placeholder' => ' סוג ', 'class' => 'form-control text-right', 'readonly'] ) !!}    </div><br>

        </div>
        <div class="float-right col-md-3 text-right ">

            </div>


   </div>





      @endsection
