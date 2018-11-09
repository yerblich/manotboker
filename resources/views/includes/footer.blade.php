<footer class="sticky-footer">
        <div class="container">
          <div class="text-center">
            <small>Copyright ©מנות בוקר 2018</small>
          </div>
        </div>
      </footer>
      <!-- Scroll to Top Button-->
      {{-- <a class="scroll-to-top rounded" href="#page-top">
        <i class="fa fa-angle-up"></i>
      </a> --}}
      <!-- Logout Modal-->
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
              <a class="btn btn-primary" href="{{ url('/login.html')}}">Logout</a>
            </div>
          </div>
        </div>
      </div>

    </div>

      <!-- Bootstrap core JavaScript-->
      <script src="{{ asset('storage/js/app.js') }}"></script>
      <script src="{{ asset('storage/js/sb-admin.js') }}"></script>
      <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

           {{-- <script src=//cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.6/highcharts.js charset=utf-8></script>
           <script src=//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js charset=utf-8></script>
            {!! $data['chart']->script() !!} --}}


                <script>

                $('.dropdown-menu').click(function(e) {
                  e.stopPropagation();
            });
      $( function() {
        $( ".datepicker" ).datepicker({
          dateFormat: "dd-mm-yy"
        });
      } );
      </script>

       <script>
         $( ".invoiceGroup" ).click(function(e) {
          e.preventDefault();
          $(this).next( ".invoiceList" ).slideToggle( "slow" );
               });
          $( ".addSupplier" ).click(function() {
          $( ".addSupplierDiv" ).slideToggle( "slow" );
        });
         $( ".addClient" ).click(function() {
          $( ".addClientDiv" ).slideToggle( "slow" );
        });
        $( ".addProduct" ).click(function() {
          $( ".addProductDiv" ).slideToggle( "slow" );
        });
        $( ".dateGroup" ).click(function() {
          $(this).next( ".orderList" ).slideToggle( "slow" );
        });


        </script>
       @if(!empty($LiveSearchArray))

      <script>
       $( function() {
        var LiveSearchArray = {!! json_encode($LiveSearchArray) !!};

  $( ".LiveSearch" ).autocomplete({
    source: LiveSearchArray
  });
} );


      </script>


      @endif

      <script>
      setTimeout(function() {
    $( ".alert" ).removeAttr( "style" ).hide('fade',3000,);
  }, 4000 );
  $('#progress').click(function(){
 var day =   $("input[name=day]").val();
var   parsha =  $("input[name=parsha]").val();
var   date =  $("input[name=date]").val();
   if(day && parsha && date ){
    $("#progressbar,  #modalbar").show();
   }

});
$('.progressMI').click(function(){

    $("#progressbar,  #modalbar").show();


});

$('#cancel').click(function(){

$("#modalbar").hide();


});
  $( function() {

    $( "#progressbar" ).progressbar({
      value: false
    });
    $( "button" ).on( "click", function( event ) {
      var target = $( event.target ),
        progressbar = $( "#progressbar" ),
        progressbarValue = progressbar.find( ".ui-progressbar-value" );

      if ( target.is( "#numButton" ) ) {
        progressbar.progressbar( "option", {
          value: Math.floor( Math.random() * 100 )
        });
      } else if ( target.is( "#colorButton" ) ) {
        progressbarValue.css({
          "background": '#' + Math.floor( Math.random() * 16777215 ).toString( 16 )
        });
      } else if ( target.is( "#falseButton" ) ) {
        progressbar.progressbar( "option", "value", false );
      }
    });
  } );

      </script>
    {{-- <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script> --}}
      <!-- Core plugin JavaScript-->
      {{-- <script src="vendor/jquery-easing/jquery.easing.min.js"></script> --}}
      <!-- Page level plugin JavaScript-->
      {{-- <script src="vendor/chart.js/Chart.min.js"></script> --}}
      <!-- Custom scripts for all pages-->
