
$('.populate').on("click",function(e){
var id  = $(this).attr('id');
var date =  $("#populate_"+id).val();

$("#progressbar,  #modalbar").show();
  $.ajax({
      type: "POST",
      url: url,
      data: { orderType:id, date:date, _token:token }
    }).done(function( data ) {

   $.each(data, function(client_id,valueObj){
     $.each(valueObj, function(prdct,qty){
        $("[name="+client_id +"_"+prdct+"]").val(qty).trigger('input');


     });

   });
$("#progressbar,  #modalbar").hide();
    });








});
