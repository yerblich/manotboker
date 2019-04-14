$(document).ready(function(){
    //group add limit
    var row = 2;

    //add more fields group
    $(".addMore").click(function(){

   $(".table").append('<tr>\
    <td><input placeholder="סה״כ" class="text-right form-control" name="credit_items['+row+'][total]" type="text" value=""></td>\
       <td><input placeholder="מחיר ליחיאה" class="text-right form-control" name="credit_items['+row+'][unit_price]" type="text" value=""></td>\
       <td><textarea placeholder="תאור" class="text-right form-control" name="credit_items['+row+'][description]" type="text" rows="1" value=""></textarea></td>\
       <td><input placeholder="כמות" class="text-right form-control" name="credit_items['+row+'][amount]" type="text"  value=""></td>\
       <th scope="row">'+row+'</th>\
   </tr>');
        row++;

    });
});
