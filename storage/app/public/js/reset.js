$(".reset").click(function(e){

  $("."+this.id).val('');


});

$(".delete").click(function(e){
 var id = e.target.id;
//document.querySelectorAll('[id = $id]');
$('input[data-productid='+id+']').val(0);

});
