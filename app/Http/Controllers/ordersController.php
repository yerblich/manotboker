<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\orderItem;
use App\Product;
use App\ProductReturn;
use App\returnItem;
use App\Price;
use App\Client;
use App\Supplier;
use Carbon\Carbon;
use Utils;
use Schema;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;


class ordersController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {



    $orderArray = [];
          $allOrders =   Order::distinct()->get(['date']);

            foreach($allOrders as $order){

                $currentDate[$order->date->format('m-Y')][]  = $order;

                $orderArray = $currentDate  ;
            }



        return view('pages.orders')->with('orders', $orderArray);
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function create(Request $request)
    {
        $products['daily'] = Product::where(['active' => 1, 'type' => '1' ])->orderBy('type', 'desc')->get();
        $products['shabbos'] = Product::where(['active' => 1, 'type' => '0' ])->orderBy('type', 'desc')->get();
        $products['american'] = Product::where(['active' => 1, 'type' => '2' ])->orderBy('type', 'desc')->get();
      if(!count($products['daily']) && !count($products['shabbos'])){
        return redirect()->route('orders.index')->with('error', 'לא נמצאו מוצרים');
      }
    //get current date iflast record doesnt exist
        date_default_timezone_set("Asia/Jerusalem");
        $currentDate =  date("d-m-Y");
        //get yesterdays order for default populate
        $lastRecord =  Order::orderBy('date', 'desc')->first();
         if($lastRecord){
            $newDateformat =   $lastRecord->date;
         }else{
            $newDateformat =  $currentDate;
         }

           //if user chose a date to populate use that date
         if($request->input("populate") !== null){

            $newDateformat =  Carbon::parse($request->input("populate"))->format('Y-m-d');

        }
           $orders = [];
     $clientList = [];
     $clientIds = [];
     $allClients = Client::orderBy('route', 'asc')->get();
    //      loop through clients and create array of the orders from yeserdays order in order to fill the form
  //  if client does not have any orders a blank array will return
   foreach($allClients as $client){
   // fetch ids and put them into client list object in order o fill the names of the inputs correctly for clients
   //not in the previous order $clientList[$client->name]
   $clientList[$client->name] =[];
    $clientIds[$client->name] = $client->id;
   //get all clients with their orders from populate date or lataest date

  $order =  $client->orders()->where('date',$newDateformat)->first();

  if($order !== null){
   $orderItems =   $order->orderItems()->get();
    foreach($orderItems as $orderItem){
        if(Product::find($orderItem->product_id)->type == 0 ){
          $orders['shabbos'][$orderItem->product_id] = $orderItem->quantity;
        }elseif(Product::find($orderItem->product_id)->type == 1){
          $orders['daily'][$orderItem->product_id] = $orderItem->quantity;
        }else{
          $orders['american'][$orderItem->product_id] = $orderItem->quantity;
        }

    }



   $clientList[$client->name] = $orders;


   } else{
    foreach($products as $orderType => $productsArray){
        foreach($productsArray as $product){
            if($product->type == 0 ){
                $orders['shabbos'][$product->id] = 0;
              }elseif($product->type == 1){
                $orders['daily'][$product->id] = 0;
              }else {
              $orders['american'][$product->id] = 0;
              }
        }


    }
    $clientList[$client->name] = $orders;
   }
}

   // get all products in order to create form
   //$products = Product::where('active', 1)->orderBy('type', 'desc')->orderBy('supplier_id')->get();

         $data = array(

        'products' =>  $products,
        'clientList' =>  $clientList,
        'populatedDate' =>  Carbon::parse($newDateformat)->format('d-m-Y'),
        'currentDate' => $currentDate,
        'clientIds' => $clientIds

      );

  //return $data;
        return view('orders.createOrder')->with("data", $data);
    }



    // public function pdfSave(Request $request)
    // {

    //     $data = array('pdf' => json_decode($request->pdf));
    //       $date = strtotime($data['pdf']->date);
    //       $date = date('Y-m-d',$date);
    //      $Udate =  $data['pdf']->date;

    //       $pdf = PDF::loadView('orders.pdfDaily', compact('data'))->save( storage_path('app/public/pdf/order'.$Udate.'.pdf')  );
    //      $pdf->setPaper('A4', 'landscape');

    //      return redirect()->route('orders.show', [$date])->with('success', 'Pdf Saved');
    // }

    public function pdfSend(Request $request)
    {


         $data = json_decode($request->pdf, TRUE);


         // $pdf = PDF::loadView('orders.pdfDaily', compact('data'))->save( storage_path('app/public/pdf/order'.$Udate.'.pdf')  );

         $data['suppliers'] =  Supplier::all();
         $date =  Carbon::parse($data['date'])->format('Y-m-d');
        $data['timesSent']  = $timesSent =   Order::where('date',$date)->first()->sent;
       foreach($data['suppliers'] as $data['supplier']){
           if($request->input($data['supplier']->id) ){


        Mail::send('orders.supplierEmail', $data, function($message) use ($data){

            $message->from('sales@manotboker.com');
            $message->to($data['supplier']->email);
            if( $data['timesSent'] > 0){
                $message->subject('order '. $data['date'].'('. $data['timesSent'].')');
            }else{
                $message->subject('order '. $data['date']);
            }

            $message->attach( url('storage/pdf/order'. $data['date'].'.pdf') );
        });
    }
       }


    $timesSent++;
       Order::where('date',$date)->update(['sent'=> $timesSent]);

         return redirect()->route('orders.show', [$date])->with('success', 'Pdf נשלחה');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

   //s return $request;
         // create array with just orders without the token and date in order to check if all fields are empty
       $requestProducts = $request->all();
        unset($requestProducts['_token'],$requestProducts['date'],$requestProducts['parsha'],$requestProducts['day']);
        $requestProducts;
     //validate date
        $this->validate($request,[
            'date' => 'required',

        ]);


        $clients =   Utils::extractClientIds($requestProducts);
        $products  = Utils::extractProductIds($requestProducts);

        // $products =  Product::where('active', 1)->get();
         foreach($products as $key => $product_id){

            if( Product::find($product_id)->type == 1){
             $sums['daily'][$product_id] = $request->input('sum_'.$product_id);
         }elseif(Product::find($product_id)->type == 0){
            $sums['shabbos'][$product_id] = $request->input('sum_'.$product_id);
         }else{
           $sums['american'][$product_id] = $request->input('sum_'.$product_id);
         }
        }

        //  foreach($request as $key => $value) {
        //      if (strpos($key, 'sum_') === 0) {
        //         $sums[$product_id] =
        //      }
        //  }

        $newDateformat = Carbon::parse($request->input("date"))->format('Y-m-d');
            $parsha = $request->input("parsha");
            $day = $request->input("day");

         //
    // check if all fields are empty , if not continue
   if(!max($requestProducts )== "") {

            foreach($clients as $client){

                        $client_order = [];
                    // check to see if a order exists with this client id and this date
                        $orderCheck = Order::where([
                            'client_id' => $client,
                            'date' => $newDateformat
                    ])->first();


                    $client_order =   Utils::createClientOrderArray($products, $request, $client);

                   // return $client_order;
                    if( isset($client_order) && !$client_order == "" ){
                        if($orderCheck == null){

                            $order =   Utils::saveOrderToDatabase($newDateformat,$client,$parsha,$day);
                            $return = Utils::saveReturnToDatabase($newDateformat,$client,$order);
                            $orderItems = Utils::saveOrderItemsAndReturnItemsToDatabase($client_order,$order,$request,$client,$return);


                            $messageCode = 'success';

                            $messageText = 'הזמנה נוצרה בהצלחה';
                        }else{
                                $messageCode = 'error';
                                $messageText = 'הזמנה כבר קיימת';

                                }

                    }

            }
        
        $this->createOrderPdf($newDateformat,$sums);

     }else{
    $messageCode = 'error';
    $messageText = 'הזמנה ריק';
    return redirect()->route('orders.create', [$newDateformat])->with($messageCode, $messageText);
   }


       return redirect()->route('orders.show', [$newDateformat])->with($messageCode, $messageText);




    }

 public function createOrderPdf($orderDate, $sums)
    {

        $parsha = Order::where('date',$orderDate)->first()->parsha;
        $day = Order::where('date',$orderDate)->first()->day;
        $suppliers = Supplier::all();
        $products = Product::all();
        $allClients =  Client::orderBy('route', 'asc')->get();
        $productNames['daily'] = [];
        $productNames['shabbos'] = [];
        $productNames['american'] = [];
           // declare resultArray which will be filled with objects that contain client name , product id and their quantiies
      $resultArray = [];
      $products_array= [];
      //loop through all clients
      foreach($allClients as $client){

        $currentListProducts =[];
       // check if this client has any orders for the date which was clcked
       if(!$client->orders()->where('date',$orderDate)->first() == null){
           //get order
       $order = $client->orders()->where('date',$orderDate)->   first();
      //get all orderitems for this order
        $orderItems[$client->name] =  orderItem::where('order_id',$order->id)->get();
       $itemlist = $orderItems[$client->name];
        foreach($itemlist as $item){
            // return $item;


               array_push($products_array,$item->product_id) ;

         }
        }

     }
    $products_array =  array_unique($products_array);


    foreach($products_array as $productId){
        if( Product::find($productId)->type == 1){
            $productNames['daily'][$productId] = Product::find($productId)->name;
        }elseif(Product::find($productId)->type == 0){
            $productNames['shabbos'][$productId] = Product::find($productId)->name;
        }else{
          $productNames['american'][$productId] = Product::find($productId)->name;
        }

    }

     foreach($allClients as $client){


       // check if this client has any orders for the date which was clcked
       if(!$client->orders()->where('date',$orderDate)->first() == null){
           //get order
       $order = $client->orders()->where('date',$orderDate)->first();
      //get all orderitems for this order
        $orderItems[$client->name] =  orderItem::where('order_id',$order->id)->get();
       $currentlist = $orderItems[$client->name];
       $app = app();
       $result = $app->make('stdClass');
       //set name to client name
       $result->name = $client->name;



       $product_array['daily'] = [];
       $product_array['shabbos'] = [];
       $product_array['american'] = [];
     // return $currentlist;
      //initialize product array

     //loop through products to create key of the array (so that all products come up , not just ordered items)
       foreach($products_array as $product){
           $product_id = $product;
           //set default 0
           $quantity = 0;
           // loop thorugh orderitems
           foreach($currentlist as $orderItem){
               // find the product inside the orderitems , if exists
               if($orderItem->product_id == $product_id ){
                  //set to quantity
                   $quantity = $orderItem->quantity;
               }
               //else its stays at default 0
           }

           //add item to product array
          if(Product::find($product_id)->type == 1){
            $product_array['daily'][$product_id] = $quantity;

          }elseif(Product::find($product_id)->type == 0){
            $product_array['shabbos'][$product_id] = $quantity;
          }else {
            $product_array['american'][$product_id] = $quantity;
          }


       }
       ///add array to object
       $result->products = $product_array;

    //  return $result->products;
    //    if( max($result->products['daily']) + max($result->products['shabbos']) > 0 ){
    //     array_push($resultArray,$result);
    //    }
       array_push($resultArray,$result);

// return $resultArray;
        }

     }
//return $sums;
      $data =   array(
        'sums' => $sums,
        'products' =>  $products,
        'productNames' =>  $productNames,
        'clientsWithOrders' => $resultArray,
        'date' =>  Carbon::parse($orderDate)->format('d-m-Y'),
        'day' => $day,
        'parsha' => $parsha,
        'suppliers' => $suppliers

      );

    $this->pdfDownload($data);
    }



    public function pdfDownload( $data)
    {

           $mpdf = new \Mpdf\Mpdf( );
       //   $data =  json_decode($request->pdf,true);
     $products = $data['productNames'];




    $productNameArray['daily'] = [];
    $productNameArray['shabbos'] = [];
      $productNameArray['american'] = [];




    $route['daily'] = [];
    $route['shabbos'] = [];
    $route['american'] = [];




    $routes['daily'] = [];
    $routes['shabbos'] = [];
    $routes['american'] = [];
    foreach($products as $productType => $array){


        $routeNum = 1;
 foreach($data['clientsWithOrders'] as $client){
  //  return $routeNum;

  $productNamePage = 1;
    $productPage = 1;
 $routes[$productType][$routeNum][] = $client;

    foreach( $client->products[$productType] as $productsid => $qty){
    $route[$productType][$routeNum][$client->name]['products'][$productPage][$productsid ] = $qty;
    if(count( $route[$productType][$routeNum][$client->name]['products'][$productPage]) == 9){
        $productPage++;
     }
    }
    if(count($routes[$productType][$routeNum]) == 35){
        $routeNum++;
 }

 }
//return $t;
    foreach($products[$productType] as $productid => $name){

     $productNameArray[$productType][$productNamePage][] = $name;
   //  return  $productNameArray;
     if(count($productNameArray[$productType][$productNamePage]) == 9){
        $productNamePage++;
     }



    }


}

    foreach ($data['sums'] as $ordertype => $sumArray) {
      $sumPage = 1;
    foreach ($sumArray as $productId => $sum) {
      $pagedSums[$ordertype][$sumPage][$productId] = $sum;
    if(count($pagedSums[$ordertype][$sumPage]) == 9){
      $sumPage++;
    }
}


  // code...
}

         $date =  $data['date'];
         $data['productNameArray'] = $productNameArray;
      $data['route'] = (array)$route;
      $data['sums'] = $pagedSums;

    //
         $mpdf = PDF::loadView('orders.pdfDaily', compact('data'));
         $mpdf->save( storage_path('app/public/pdf/order'.$date.'.pdf')  );


    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($orderDate)
    {


      $data =   array(


        'date' =>  Carbon::parse($orderDate)->format('d-m-Y'),
        'suppliers' => Supplier::all()

      );
    //   return $data;

       return view('orders.dayOrder')->with("data", $data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($date)
    {
        $products['daily'] = Product::where(['active' => 1, 'type' => '1' ])->orderBy('type', 'desc')->get();
        $products['shabbos'] = Product::where(['active' => 1, 'type' => '0' ])->orderBy('type', 'desc')->get();
          $products['american'] = Product::where(['active' => 1, 'type' => '2' ])->orderBy('type', 'desc')->get();

            $orderDate =  Carbon::parse($date)->format('Y-m-d');

              $parsha = Order::where('date',$orderDate)->first()->parsha;
            $day = Order::where('date',$orderDate)->first()->day;
       //get al clients
     $clientList = [];
     $clientIds = [];
    $allClients =  Client::orderBy('route', 'asc')->get();


    //      loop through clients and create array of the orders from yeserdays order in order to fill the form
  //  if client does not have any orders a blank array will return
   foreach($allClients as $client){
    $clientList[$client->name] = [];
   // fetch ids and put them into client list object in order o fill the names of the inputs correctly for clients
   //not in the previous order
   $clientIds[$client->name] = $client->id;
   //get all clients with their orders from populate date or lataest date
   if($client->orders()->where('date',$orderDate)->first()){
    $id =  $client->orders()->where('date',$orderDate)->first()->id;
    $items =   orderItem::where('order_id',$id)->get();
    foreach($items as $item){


             $clientList[$client->name][$item->product_id] = $item->quantity;



     }
}




   }


   // get all products in order to create form
   //$products = Product::where('active', 1)->orderBy('type', 'desc')->orderBy('supplier_id')->get();

         $data = array(
        'date' => $orderDate,
        'day' => $day,
        'parsha' => $parsha,
        'products' =>  $products,
        'clientList' =>  $clientList,
        'clientIds' => $clientIds

      );
   //   return $data;
    return view('orders.editOrder')->with("data", $data);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $date)
    {

      $requestProducts = $request->all();
        unset($requestProducts['_token'],$requestProducts['_method']);

     $clients = Utils::extractClientIds($requestProducts);
     $products =  Product::where('active', 1)->get();
     $orderDate =  Carbon::parse($date)->format('Y-m-d');

     $products  = Utils::extractProductIds($requestProducts);

        // $products =  Product::where('active', 1)->get();
         foreach($products as $key => $product_id){
            if( Product::find($product_id)->type == 1){
             $sums['daily'][$product_id] = $request->input('sum_'.$product_id);
         }elseif(Product::find($product_id)->type == 0){
            $sums['shabbos'][$product_id] = $request->input('sum_'.$product_id);
         }else {
           $sums['american'][$product_id] = $request->input('sum_'.$product_id);
         }
        }

     // check if all fields are empty , if not continue
    if(!max($requestProducts )== "") {
        //
             foreach($clients as $client){


                             // check to see if a order exists with this client id and this date
                             $client_order =   Utils::createClientOrderArray($products, $request, $client);

                // return $client_order;

                                 // after we see order is not empty check if it already exists , if not ...
                                   if(Order::where(['date' => $orderDate, 'client_id' => $client])->first()){
                                    $order =  Order::where(['date' => $orderDate, 'client_id' => $client])->first();
                                    $return = ProductReturn::where('order_id' , $order->id)->first();
                                    $order->update(['client_id' => $client]);


                                       $orderItems = $order->orderItems()->get();
                                       foreach ($orderItems as $orderItem) {
                                        if(!array_key_exists($orderItem->product_id,$client_order)){

                                            returnItem::where('order_items_id',$orderItem->id)->delete();
                                            $orderItem->delete();
                                                }

                                       }

                                         foreach($client_order as $product_id => $quantity){
                                         $orderItem =    orderItem::where(['order_id' => $order->id, 'product_id' => $product_id])->first();
                                           if(!$orderItem == ''){
                                            $orderItem->update(['quantity' => $quantity]);
                                           }else{
                                            $orderItem = new orderItem;
                                            $orderItem->order_id = $order->id;
                                            $orderItem->product_id = $product_id;
                                             $orderItem->quantity = $quantity;

                                          $orderItem->currentPrice = Price::where(['client_id' => $client, 'product_id' => $product_id])->first()->price;
                                            $orderItem->save();

                                            $returnItem = new returnItem;
                                            $returnItem->order_items_id = $orderItem->id;
                                            $returnItem->product_return_id = $return->id;
                                            $returnItem->product_id = $product_id;
                                            $returnItem->quantity = 0;
                                            $returnItem->currentPrice = Price::where(['client_id' => $client, 'product_id' => $product_id])->first()->price;
                                            $returnItem->save();
                                           }




                                         }
                                        }else{
                                            if( isset($client_order) && !$client_order == "" ){


                                                $parsha = $request->input('parsha');
                                                $day =  $request->input('day');
                                     $order =   Utils::saveOrderToDatabase($orderDate,$client, $parsha, $day);
                                  $return = Utils::saveReturnToDatabase($orderDate,$client,$order);
                                  $orderItems = Utils::saveOrderItemsAndReturnItemsToDatabase($client_order,$order,$request,$client,$return);


                                            }


                                        }
                                         //save



                                 }



                                 $this->createOrderPdf($orderDate, $sums);
                                 $messageCode = 'success';
                                 $messageText = 'הזמנה עודכן בהצלחה';


                         // if the max of all the orders = nothing it means no order was placed for this client so skip, otherwise continue




      }else{
     $messageCode = 'error';
     $messageText = 'הזמנה ריק';
     return redirect()->route('orders.edit', [$orderDate])->with($messageCode, $messageText);
    }


        return redirect()->route('orders.show', [$orderDate])->with($messageCode, $messageText);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($date)
    {

      Storage::delete('public/pdf/order'.$date.'.pdf');
      $date =  Carbon::parse($date)->format('Y-m-d');
     $orders =  Order::where('date', $date)->get();
     $returns =  ProductReturn::where('date', $date)->get();
     foreach($orders as $order){
         $order->orderItems()->delete();
         $order->delete();
     }
     foreach($returns as $return){
        $return->returnItems()->delete();
        $return->delete();
    }


    return redirect()->route('orders.index')->with('success', 'הזמנה נמחקה');
    }






}
