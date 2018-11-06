$(".overwriteAlert").click(function(){
    var x =  confirm("An Invoice for these dates already exist , Press Ok To overwrite ?");
    if(x){
      $("#progressbar,  #modalbar").show();
    }else{
      return false;
    }

});
