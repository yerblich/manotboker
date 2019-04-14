@extends('layouts.main')

@section('content')
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">זיכויים</a>
      {{-- {{$data['message']}} --}}
    </li>
    <li class="breadcrumb-item">
      <a href="#">{{$client->name}}</a>
      {{-- {{$data['message']}} --}}
    </li>
    <li class="breadcrumb-item">
        <a href="#">זיכוי - {{$credit->id}}</a>
        {{-- {{$data['message']}} --}}
      </li>
    <style>
        tr:nth-child(even) {background: #CCC}
      tr:nth-child(odd) {background: #FFF}
      table, th, td {
         border: 1px solid black;

      }
      @font-face {
	font-family: 'Ezra SIL SR';
	src: url('fonts/ezrasilsr-webfont.eot?#iefix') format('embedded-opentype'),
	     url('fonts/ezrasilsr-webfont.woff') format('woff'),
	     url('fonts/ezrasilsr-webfont.ttf')  format('truetype'),
	     url('fonts/ezrasilsr-webfont.svg#EzraSILSR') format('svg');
    }
    .test{
        font-family: 'Ezra SIL SR'
    }
    </style>
  </ol>


<hr>

<div class="row">

    <div class="col-12">
  <object  data="{{asset("storage/creditPdfs/".$client->id."/credit".$credit->id.".pdf")}}" type="application/pdf" width="100%" height="500">
    <iframe id = "pdfFrame" name="pdfFrame"    src="{{asset("storage/creditPdfs/".$client->id."/credit".$credit->id.".pdf")}}" width="100%" height="600"></iframe>
  </object>
  </div>
  </div>
  <div id="modalbar" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>

        </div>
        <div class="modal-body">
          Print Success ? press yes to confirm
          <button onclick="printSuccess()"> Yes </button> <button onclick="printFail()"> No </button>

        </div>
        <div class="modal-footer">

        </div>
      </div>
    </div>
  </div>
      @endsection
      @section('js')

      <script src={{ asset('storage/js/deleteAlert.js') }}></script>
      <script src={{ asset('storage/js/printInvoice.js') }}></script>



    @stop
