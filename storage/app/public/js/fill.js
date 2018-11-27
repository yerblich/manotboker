$(".fill").change(function(e){
  var x  = $(this).val();
  var name  = $(this).attr('name');
  var escapedname = (name + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
 let a =   $("."+escapedname).val(x);

});
