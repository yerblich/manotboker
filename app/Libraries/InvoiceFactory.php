<?php
namespace App\Libraries;
use Illuminate\Http\Request;
use App\Invoice;
use App\Client;
use App\Product;
use App\Price;
use App\orderItem;
use App\returnItem;
use App\Supplier;
use Carbon\Carbon;
use DB;


class InvoiceFactory{

public static function getCurrentIncrement(){
  $tableStatus =   DB::select("show table status where name='invoices' ");
  $currentIncrement =  $tableStatus[0]->Auto_increment;
  $invoiceId = $currentIncrement ;
  return $invoiceId;
}



public static function generateInvoice($clientId,$from_date,$to_date, $invoiceId){

  $client = Client::find($clientId);
     //get all orders between date of created invoice
        $orders = $client->orders()->whereBetween('date',[$from_date,$to_date])->get();
 if(!$orders->count()){
   $orders = null;
 }
      $orderIds = $client->orders()->whereBetween('date',[$from_date,$to_date])->pluck('id')->toArray();
     //""
      $returns = $client->returns()->whereBetween('date',[$from_date,$to_date])->get();
     $returnIds = $client->returns()->whereBetween('date',[$from_date,$to_date])->pluck('id')->toArray();

      //initialize
         $totalToPay = [];
         $products = Product::all();
         $productTotal = [];
         $productReturnTotal = [];
         $products_array = [];
         $orders_array = [];
         $returns_array = [];
         $productNames = [];
         $invoiceInfo =[];

         // get all products that exist in this invoice
        $allProductsInInvoice = orderItem::whereIn('order_id', $orderIds)->pluck('product_id')->toArray();
        //remove duplicates
        $products_array  = array_unique($allProductsInInvoice);
         //go through each product , and find any price changes within the orders and return array
         foreach($products_array as $productId){
             $totalTemp = [];
            $qtyOfItemOrdered[$productId] =   array_sum(orderItem::whereIn('order_id', $orderIds)->where('product_id', $productId)->pluck('quantity')->toArray()) ;

            $qtyOfReturns[$productId] =   array_sum(returnItem::whereIn('product_return_id', $returnIds)->where('product_id', $productId)->pluck('quantity')->toArray()) ;

            $priceChanges = orderItem::whereIn('order_id', $orderIds)->where('product_id', $productId)->pluck('currentPrice')->toArray();
             $priceAndQty = [];
             // go through the price changes -
             foreach(array_unique($priceChanges) as $prc){
                //and find quantity of this product from all orders

            $orderQtys =  orderItem::whereIn('order_id', $orderIds)->where(['product_id' => $productId, 'currentPrice' => $prc])->pluck('quantity')->toArray();
              $returnQtys =  returnItem::whereIn('product_return_id', $returnIds)->where(['product_id' => $productId, 'currentPrice' => $prc])->pluck('quantity')->toArray();
            // sum them up , and put into array
             $priceAndQty[$prc] = array_sum($orderQtys) -   array_sum($returnQtys);
             }


             foreach($priceAndQty as $price => $qty){

               array_push($totalTemp,$price * $qty);
             }

            $total =  array_sum($totalTemp);
              // make new array with product containing price:quantity
             $allCurrentPrices[$productId] = $priceAndQty;
             $totalToPay[$productId]['totalToPay'] = $total;
                }



     //  get names of products in order to fill form
         foreach($products_array as $productId){

             $productNames[$productId] = Product::find($productId)->name;
              $productNames;
         }



      $row = 0;
     foreach($products_array as $product_id ){
       $row++;
         $units = Product::find($product_id)->units;
         $name =   Product::find($product_id)->name;
          $totalToPayForProduct = $totalToPay[$product_id]['totalToPay'];
      //    return $allCurrentPrices[$product_id];
         $invoiceInfo[$name] = array(
           'row' =>  $row,
           'barcode' =>   Product::find($product_id)->barcode,
              'price' =>$allCurrentPrices[$product_id],
              'ordered' => $qtyOfItemOrdered[$product_id],
              'units' =>   $units,
              'returns' => $qtyOfReturns[$product_id],
              'totalSold' => $qtyOfItemOrdered[$product_id] - $qtyOfReturns[$product_id],
              'totalToPayForProduct' => $totalToPayForProduct
          );



        }

     $totalToPay =  array_sum(array_column($totalToPay, 'totalToPay'));



 return       $data = array(
          // 'paid' => $invoice->paid,
          'isOriginal' => true,
         'invoiceId' => $invoiceId,
         'client' => $client,
         'orders' => $orders,
         'productNames' =>  $productNames,
         'products'=> $products,
         'from_date' => Carbon::parse($from_date)->format('d-m-Y'),
         'to_date' => Carbon::parse($to_date)->format('d-m-Y'),
         'invoiceInfo' => $invoiceInfo,
         'totalToPay' => $totalToPay
     );



}












}
