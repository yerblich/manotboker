$('#printInvoice').click(function(e){

e.preventDefault();
  document.getElementById("pdfFrame").contentWindow.print();
$('#printInvoice').data('clicked', true);
window.onfocus = function () {

   if($('#printInvoice').data('clicked') === true){
       $("#progressbar,  #modalbar").show();
   };






                }
});
 function printSuccess(){
   setTimeout(function(){
     $("#progressbar,  #modalbar").hide();
       $('#postPrint').submit();
     }, 1000);

 }

 function printFail(){
    $("#progressbar,  #modalbar").hide();
  $('#printInvoice').data('clicked', false);
 }
