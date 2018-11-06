$(document).ready(function(){
    $(".liveFilter").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $(".liveFilterTable tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
  ///testcffc
