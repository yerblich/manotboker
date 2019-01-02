<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductReturn;
use App\PrevReturn;
use App\PrevReturnItem;
use App\Client;
use App\Order;
use App\Product;
use App\Price;
use App\returnItem;
use App\orderItem;
use Carbon\Carbon;
use Schema;
use DB;
use Utils;

class ReturnsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $returnArray = [];
        $allReturns =   ProductReturn::distinct()->orderBy('date')->get(['date']);
        foreach($allReturns as $return){

            $currentDate[$return->date->format('m-Y')][]  = $return;

            $returnArray = $currentDate  ;
        }

        return view('pages.returns')->with('returns', $returnArray);
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


          $newDateformat =  Carbon::parse($request->input('date'))->format('Y-m-d');


    $returns = [];
    $clientList = [];
    $clientIds = [];
    $allClients = Client::orderBy('name', 'asc')->get();
    //      loop through clients and create array of the orders from yeserdays order in order to fill the form
    //  if client does not have any orders a blank array will return
    foreach($allClients as $client){
    // fetch ids and put them into client list object in order o fill the names of the inputs correctly for clients
    //not in the previous order $clientList[$client->name]
    $clientList[$client->name] =[];
    $clientIds[$client->name] = $client->id;

    if ($request->input('prevMonth') == 1) {
      $return =  $client->PrevReturns()->where('date',$newDateformat)->first();

    }else{
      $return =  $client->returns()->where('date',$newDateformat)->first();

    }

    foreach($products as $orderType => $productsArray){

    foreach($productsArray as $product){
      $quantity = '';
      if($return !== null){
        if ($request->input('prevMonth') == 1) {
          $returnItem = $return->PrevreturnItems()->where('product_id', $product->id)->first();

        }else{
          $returnItem = $return->returnItems()->where('product_id', $product->id)->first();

        }
        if ($returnItem !== null ) {
          $quantity = $returnItem->quantity;
        }else{

        }
      }


        if($product->type == 0 ){
            $returns['shabbos'][$product->id] = $quantity;
          }elseif($product->type == 1){
            $returns['daily'][$product->id] = $quantity;
          }else {
          $returns['american'][$product->id] = $quantity;
          }
    }


    }
    $clientList[$client->name] = $returns;

    }

    // get all products in order to create form
$prevMonth = 0;    //$products = Product::where('active', 1)->orderBy('type', 'desc')->orderBy('supplier_id')->get();
if ($request->input('prevMonth') == 1) {
$prevMonth = 1;
}
       $data = array(
      'prevMonth' =>  $prevMonth,
      'products' =>  $products,
      'clientList' =>  $clientList,
      'date' => Carbon::parse($newDateformat)->format('d-m-Y'),
      'clientIds' => $clientIds

    );

    //return $data;
      return view('returns.createReturn')->with("data", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


               // create array with just orders without the token and date in order to check if all fields are empty
             $requestProducts = $request->all();
              unset($requestProducts['_token'],$requestProducts['date'],$requestProducts['prevMonth']);
              $requestProducts;
           //validate date
              $this->validate($request,[
                  'date' => 'required',

              ]);


              $clients =   Utils::extractClientIds($requestProducts);
              $products  = Utils::extractProductIds($requestProducts);

              // $products =  Product::where('active', 1)->get();
               foreach($products as $key => $product_id){
                 if($request->input('sum_'.$product_id) > 0 ){

                    $sums[Product::find($product_id)->name] = $request->input('sum_'.$product_id);

                 }



              }



                $newDateformat = Carbon::parse($request->input("date"))->format('Y-m-d');



          // check if all fields are empty , if not continue


                  foreach($clients as $client){


                              $client_return = [];
                              $returnItem = '';
                          // check to see if a return exists with this client id and this date


                          $client_return =   Utils::createClientReturnArray($products, $request, $client);


                          $returnCheck = ProductReturn::where([
                              'client_id' => $client,
                              'date' => $newDateformat,

                        ])->first();

                        $prevReturnCheck = PrevReturn::where([
                            'client_id' => $client,
                            'date' => $newDateformat,

                      ])->first();
                      if ($request->input('prevMonth') == 1) {
                        $returnCheck = $prevReturnCheck;
                      }else{
                        $returnCheck = $returnCheck;
                      }


                          if( isset($client_return) && !$client_return == "" ){


                              if($returnCheck == ''){
                                if ($request->input('prevMonth') == 1) {
                                  $return =   Utils::savePrevReturnToDatabase($newDateformat,$client);
                                  $returnItems = Utils::savePrevReturnItemsToDatabase($client_return,$return,$request,$client);

                                }else{
                                  $return =   Utils::saveReturnToDatabase($newDateformat,$client);
                                  $returnItems = Utils::saveReturnItemsToDatabase($client_return,$return,$request,$client);

                                }



                                  $messageCode = 'success';

                                  $messageText = 'created';
                              }else{

                                      foreach($client_return as $productId => $quantity){
                                        if ($request->input('prevMonth') == 1) {
                                          $returnItem =   PrevReturnItem::where(['prev_return_id' => $returnCheck->id, 'product_id' => $productId ])->first();

                                        }else{
                                          $returnItem =   returnItem::where(['product_return_id' => $returnCheck->id, 'product_id' => $productId ])->first();

                                        }



                                       if ($returnItem == '') {
                                         if ($request->input('prevMonth') == 1) {
                                           $returnItem = new prevReturnItem;
                                           $returnItem->prev_return_id = $returnCheck->id;
                                           $returnItem->product_id = $productId;
                                           $returnItem->quantity = $quantity;
                                           $returnItem->currentPrice = Price::where(['client_id' => $client, 'product_id' => $productId])->first()->price;
                                           $returnItem->save();
                                         }else {
                                           $returnItem = new returnItem;
                                           $returnItem->product_return_id = $returnCheck->id;
                                           $returnItem->product_id = $productId;
                                           $returnItem->quantity = $quantity;
                                           $returnItem->currentPrice = Price::where(['client_id' => $client, 'product_id' => $productId])->first()->price;
                                           $returnItem->save();
                                         }

                                       }else{

                                         $returnItem->update([
                                           'quantity' => $quantity
                                         ]);
                                       }



                                      }

                                      $messageCode = 'success';
                                      $messageText = 'Updated';

                                      }

                          }

                  }









       return redirect()->route('returns.index' )->with($messageCode, $messageText);




    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($returnDate)
    {
        $productsArray['daily'] =[];
        $productsArray['shabbos'] = [];
        $productsArray['american'] = [];
        //get all orders from this date
        $orders =  Order::where('date',$returnDate)->pluck('id')->toArray();
       $client_ids =  Order::where('date',$returnDate)->pluck('client_id')->toArray();
       foreach ( $client_ids as $client_id) {
        $client =   Client::find($client_id);
         $clients[] = $client;

       }

        // get array of all products in the orders from this date
      $orderItems =   orderItem::whereIn('order_id', $orders)->pluck('product_id')->toArray();
      //erase duplicates
     $allproducts = array_unique($orderItems);

      foreach($allproducts as $product){
          if(Product::find($product)->type == 0){
            array_push($productsArray['shabbos'] ,Product::find($product));
          }elseif(Product::find($product)->type == 1){
            array_push($productsArray['daily'] ,Product::find($product));
          }else{
            array_push($productsArray['american'] ,Product::find($product));
          }
      }

//return $productsArray;
      //  $clientList = [];
        //      loop through clients and create array of the orders from yeserdays order in order to fill the form
      //  if client does not have any orders a blank array will return
      $resultArray = [];
      foreach($clients as $client){


        // check if this client has any returns for the date which was clcked
        if(!$client->returns()->where('date',$returnDate)->first() == null){
            //get order
        $return = $client->returns()->where('date',$returnDate)->first();
       //get all returnitems for this return
         $returnItems[$client->name] =  returnItem::where('product_return_id',$return->id)->get();
         //create object
         $app = app();
         $result = $app->make('stdClass');
         //set name to client name
         $result->name = $client->name;
         $result->id = $client->id;

         foreach($returnItems[$client->name] as $returnItem){
         $allQty[$returnItem->product_id] = $returnItem->quantity;
          }

//return $allQty;
        //initialize product array
       $product_array = [];
       //loop through products to create key of the array (so that all products come up , not just ordered items)
         foreach($productsArray as $orderType => $products ){


            $product_array[$orderType] =[];

            foreach($products as $product){
                $quantity = 0;
              if (array_key_exists($product->id,$allQty))
                  {
                    if($allQty[$product->id] > 0){
                      $quantity = $allQty[$product->id];
                    }
                  }


             //add item to product array
             $product_array[$orderType][$product->id] = $quantity;
            }
         }
         ///add array to object
         $result->products = $product_array;
         ///add order to result array
         array_push($resultArray,$result);

        }


        }



   $data = array(

        'products' =>  $productsArray,
        'clientsWithOrders' => $resultArray,
        'date' =>  Carbon::parse($returnDate)->format('d-m-Y')

      );

   // return $data;
       return view('returns.dayReturn')->with("data", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
