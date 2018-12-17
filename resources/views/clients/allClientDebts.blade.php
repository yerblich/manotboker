@extends('layouts.main')
@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
      <li class="breadcrumb-item">
          <a href="">לקוחות</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#"></a>
          </li>

  </ol>




  <div class="row">

    <div class="col-12">
  <object  data="{{asset("storage/admin/allClientDebts.pdf")}}" type="application/pdf" width="100%" height="500">
    <iframe src="{{asset("storage/admin/allClientDebts.pdf")}}" width="100%" height="600"></iframe>
  </object>
  </div>
  </div>




      @endsection
      @section('js')

        <script src={{ asset('storage/js/deleteAlert.js') }}></script>




      @stop
