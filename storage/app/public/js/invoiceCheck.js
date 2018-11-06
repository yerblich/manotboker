

//testdbhdbd 

$('#modalpress').on("click",function(e){

    var from_date =  $("input[name=from_date]").val();
    var to_date = $("input[name=to_date]").val();

      $.ajax({
          type: "POST",
          url: url,
         data: { from_date: from_date,to_date:to_date, _token:token }
        }).done(function( data ) {
          if(data['from_date'] == 'duplicate'){
           var t =  confirm('אחת או יותר חשבוניות קיימים כבר לחץ על אישור כדי לעדכן');
           if(!t){
            return false;
           }else{
              $("#progressbar,  #modalbar").show();
            $('#generateForm').submit();
           }




          }else{
               $("#progressbar,  #modalbar").show();
            $('#generateForm').submit();
          }


        });
  //$("#modalbar").show();


  });
