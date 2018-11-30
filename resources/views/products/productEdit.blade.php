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
  <div class="col-md-1">{!! Form::open(['action'=> ['ProductsController@destroy',$data['id']], 'method' => 'POST']) !!}
    @method('DELETE')
    {{ csrf_field() }}
    {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}

    {{Form::submit('הסר', ['class' => 'deleteAlert btn btn-primary'])}}
    {!! Form::close() !!} </div>
  {!! Form::open(['action'=> ['ProductsController@update',$data['id']] ,'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
  {{ method_field('PUT') }}
  {!! csrf_field() !!}


        <div class="  col-md-1 ">{{Form::submit('עדכון', ['class' => 'btn btn-primary'])}} </div>
  </div>


<div class="row ">

        <div class="float-right col-md-2 text-right ">

        </div>
        <div class="float-right col-md-4 text-right ">
          @if($data['imageName'] == 'noimage.jpg')
               <img  width = "90%"class="img  " src="/storage/productImages/{{$data['imageName']}}">
          @else
                <img  width = "90%"class="img  " src="/storage/productImages/{{$data['supplier']}}/{{$data['imageName']}}">
            @endif
                {!!Form::file('product_image', ['class' => 'form-control btn btn-priamry'])!!}
        </div>
        <div class=" form-group input-group col-md-3  mb-3  ">

          {{-- <div class=" float-right text-right"> ספק :{{ $data['supplier']}}  </div><br> --}}
          {{-- <div class="    text-right"> מספר מוצ {!! Form::text('poructId',$data['id'],['class' => 'form-control text-right' ,'readonly'] ) !!}    </div><br> --}}

          <div class=" form-group input-group  ">
             {!! Form::text('productName',$data['supplier'],[ 'class' => 'form-control text-right', 'readonly'] ) !!}
             <div class="input-group-append">
                 <span class="input-group-text">ספק</span>
               </div>
             </div>

          <div class=" form-group input-group  ">
             {!! Form::text('productName',$data['id'],[ 'class' => 'form-control text-right', 'readonly'] ) !!}
             <div class="input-group-append">
                 <span class="input-group-text">מספר מוצר</span>
               </div>
             </div>

          <div class=" form-group input-group  ">
             {!! Form::text('productName',$data['name'],['placeholder' => 'שם מוצר ', 'class' => 'form-control text-right'] ) !!}
             <div class="input-group-append">
                 <span class="input-group-text">שם מוצר</span>
               </div>
             </div>
          <div class=" input-group form-group  ">
              {!! Form::text('weight',$data['weight'],['placeholder' => 'משקל ', 'class' => 'form-control text-right'] ) !!}
              <div class="input-group-append">
                  <span class="input-group-text">גרם</span>
                </div>
               </div>
             <div class=" input-group form-group  ">
                 {!! Form::text('units',$data['units'],['placeholder' => ' כמות באריזה', 'class' => 'form-control text-right'] ) !!}
                 <div class="input-group-append">
                     <span class="input-group-text">כמות באריזה</span>
                   </div>
                  </div>

          <div class=" form-group input-group   ">
              {!! Form::input('number','supplierPrice',$data['supplierPrice'],['step'=>'0.1','onkeypress'=> "return isNumberKey(event)",'placeholder' => 'מחיר ', 'class' => 'form-control text-right'] ) !!}

              <div class="input-group-append">
                  <span class="input-group-text">&#8362;</span>
                </div>
          </div>
          <div class=" form-group input-group   ">
              {!! Form::input('number','barcode',$data['barcode'],['onkeypress'=> "return isNumberKey(event)",'placeholder' => 'ברקוד','class' => 'text-right form-control'] ) !!}

              <div class="input-group-append">
                  <span class="input-group-text">ברקוד</span>
                </div>
          </div>
          <div class="input-group form-group ">
             {!! Form::select('type',[
                   0 => 'שבת' ,
                   1 => 'יומי',
                   2 => 'אמריקאי'],
                   $data['type'],['class' => 'form-control custom-select'] ) !!}
             <div class="input-group-append">
                 <span class="input-group-text">סוג</span>
               </div>
             </div>



        </div>
        <div class=" col-md-3 text-right ">

            </div>



            {{-- <a class="btn btn-primary" href="{{ url('/orders/pdf')}}">Download</a> --}}




   </div>

   {!! Form::close() !!}




      @endsection
      @section('js')

      <script src={{ asset('storage/js/deleteAlert.js') }}></script>
      <script src={{ asset('storage/js/isNumberKey.js') }}></script>



    @stop
