$('.insertZero').keyup(function(e){
   var code = e.keyCode ;
  if($(this).val() === ''){
    if (code !== 9) {
        $(this).val(0);
    }

  }

})
