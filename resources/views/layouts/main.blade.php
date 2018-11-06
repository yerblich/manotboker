
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Manot Boker</title>



  {{-- <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> --}}

  <link href="{{ asset('storage/css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('storage/css/custom.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- Custom fonts for this template-->
  <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
  {{-- <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"> --}}
  <!-- Custom styles for this template-->
 <link href="{{ asset('storage/css/sb-admin.css') }}" rel="stylesheet">

      {{-- <script src=//cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.6/highcharts.js charset=utf-8></script>
      <script src=//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js charset=utf-8></script>
       {!! $data['chart']->script() !!} --}}



</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
        @include('includes.nav')
    <div class="content-wrapper">
     <div class="container-fluid">
        @include('includes.message')
       @yield('content')
     </div>
    </div>
 @include('includes.footer')
 @yield('overwriteAlert')
 @yield('sum')
 @yield('options')
 @yield('js')
</body>
</html>
