$( function() {
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
  } );


$("#getRoute").on('click', function(){
    var routeArray = [];
    $('#sortable li').each(function(){
        routeArray.push($(this).attr('id'));
       
    });
    $('.route').val(routeArray); 
   
});


setTimeout(function() {
    $( ".alert" ).removeAttr( "style" ).hide('fade',3000,);
  }, 1000 );

 
// alert(routeArray);