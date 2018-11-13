<?php
namespace App\Libraries;

use App\Order;
use App\orderItem;
use App\ProductReturn;
use App\returnItem;
use App\Price;
use App\Product;


class Utils
{
    public static function saveOrderToDatabase($newDateformat,$client,$parsha,$day){
        $order  = new Order;
        $order->date = $newDateformat;
        $order->parsha = $parsha;
        $order->day = $day;
       $order->client_id = $client;
       $order->save();
       return $order;
      }




      public static function saveReturnToDatabase($newDateformat,$client, $order){
        $return = new ProductReturn;
        $return->date = $newDateformat;
        $return->order_id = $order->id;
        $return->client_id = $client;
        $return->save();
        return $return;
       }



       public static function saveOrderItemsAndReturnItemsToDatabase($client_order,$order,$request,$client,$return){

        foreach($client_order as $productId => $quantity){
            $orderItem = new orderItem;
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $productId;
             $orderItem->quantity = $request->input($client ."_". $productId);
             $orderItem->c_supplier_price = Product::where( 'id' ,$productId)->first()->supplier_price;
          $orderItem->currentPrice = Price::where(['client_id' => $client, 'product_id' => $productId])->first()->price;
            $orderItem->save();

            $returnItem = new returnItem;
            $returnItem->order_items_id = $orderItem->id;
            $returnItem->product_return_id = $return->id;
            $returnItem->product_id = $productId;
            $returnItem->quantity = 0;
            $returnItem->currentPrice = Price::where(['client_id' => $client, 'product_id' => $productId])->first()->price;
            $returnItem->save();



        }
       }

       public static function extractClientIds($requestArray){

        foreach($requestArray as $ClientId_ProductId => $amount) {
            if (is_numeric($ClientId_ProductId[0])) {
                if($amount !== null){
                    $temp[]  = strtok( $ClientId_ProductId,  '_');
                    $allTheRest[] = strtok( '' );
                }
            }

           }
          return  array_unique($temp);
    }

       public static function extractProductIds($requestArray){

        foreach($requestArray as $ClientId_ProductId => $amount) {
            if (is_numeric($ClientId_ProductId[0])) {
                if($amount !== null){
                    $temp[]  = strtok( $ClientId_ProductId,  '_');
                    $allTheRest[] = strtok( '' );
                }

            }

           }
          return  array_unique($allTheRest);
    }

    public static function createClientOrderArray($products, $request, $client){
        $client_order = [];
        foreach($products as $key => $product_id ){

            if(!$request->input($client ."_". $product_id) == null){

                $client_order[$product_id] =  $request->input($client ."_". $product_id);

         }
        }
        return $client_order;
    }

public static function prefix_product($supplierName,$productNameInput,$type){
    $prefix = $supplierName;
      if(!strpos( $productNameInput,$prefix) === 0 ){
        $productFix  = $prefix .'_'. str_replace(' ', '_', $productNameInput)     ;
      }  else{
          $productFix = $productNameInput;
      }
   if($type == 0){
       $productFix =  $productFix ."_ש" ;
   }
return $productFix;
}



}
