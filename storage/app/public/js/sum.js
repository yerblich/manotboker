

$(".test").on('input', function(){

  var classes = this.className.split(' ')[0];
    var productId = classes.split("_").pop();

    calc_total(productId);
  });


function calc_total(productId){

    var sum = 0;
    $(".amount_"+productId).each(function(){
        if(parseFloat($(this).val()) > 0){
            sum += parseFloat($(this).val());
        }

    });
    $('.sum_'+productId).val(sum);
  }
  $(document).ready(function(){
    $(".test").each(function(){
        var classes = this.className.split(' ')[0];
        var productId = classes.split("_").pop();

        calc_total(productId);
    });

    });
