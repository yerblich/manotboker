
$('.order').on("submit",function(e){


let day = $(this).find('input[name="day"]').val();
 let parsha = $(this).find('input[name="parsha"]').val();
 let date = $(this).find('input[name="date"]').val();
     var result = { };
$.each($(this).serializeArray(), function() {
  if(this.value > 0 ){
    result[this.name] = this.value;
  }

});


  //

  $.ajax({
      type: "POST",
      url: url,
      data: { data: result, date:date, _token:token }
    }).done(function( data ) {
    window.location = surl + '/' + data.date;


    });


return false;





});
