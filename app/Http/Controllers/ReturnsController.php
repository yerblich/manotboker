<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductReturn;
use App\Client;
use App\Order;
use App\Product;
use App\Price;
use App\returnItem;
use App\orderItem;
use Carbon\Carbon;
use Schema;
use DB;

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

//         date_default_timezone_set("Asia/Jerusalem");
//         $currentDate =  date("d-m-Y");
//
//
//
//        //get al clients
//
//     $allClients =  Client::orderBy('route', 'asc')->get();
//
//
// // return $allClients;
//    // get all products in order to create form
//    $products = Product::where('active' , 1)->get();
//          $data = array(
//
//         'products' =>  $products,
//         'clientList' =>  $allClients,
//
//         'currentDate' => $currentDate
//
//       );
//
//
//         return view('returns.createReturn')->with("data", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //return $request;


        $requestProducts = $request->all();
        unset($requestProducts['_token'],$requestProducts['date'],$requestProducts['parsha']);

     //validate date
        $this->validate($request,[
            'date' => 'required',

        ]);


   // return $request->all();
    //  select first charachters of name attribute which corresponds to client id
      foreach($requestProducts as $key => $value) {
            $temp[]  = strtok( $key,  '_');

          }

          //remove duplicate
             $trimmedList = array_unique($temp);
      //remove everything but numbers
   $clients = array_filter($trimmedList, 'is_numeric');



    $date = strtotime($request->input("date"));
    $newDateformat = date('Y-m-d',$date);


      $orders =  Order::where('date',$newDateformat)->pluck('id')->toArray();
      $orderItems =   orderItem::whereIn('order_id', $orders)->pluck('product_id')->toArray();
      //erase duplicates
  $products = array_unique($orderItems);
  // $returnIds =   ProductReturn::where('date',$newDateformat)->pluck('id')->toArray();
  //   returnItem::whereIn(['product_return_id' => $returnIds)->update(['quantity' => 0]);

    // check if all fields are empty , if not continue
   // if(!max($requestProducts )== "") {
       //
            foreach($clients as $client){
                            // check to see if a return exists with this client id and this date
                             $return = ProductReturn::where([
                                    'client_id' => $client,
                                    'date' => $newDateformat
                            ])->first();
                            //select return of current client
                            $quantity =  0;
                            foreach($products as $productId ){

                              if($request->input($client ."_". $productId) == null){
                                  $client_return[$client][$productId] =  $quantity;
                              }else{
                                $client_return[$client][$productId] =  $request->input($client ."_". $productId);


                              }
                            }



                                        foreach($client_return[$client] as $productId => $quantity){

                                            returnItem::where(['product_return_id' => $return->id, 'product_id' => $productId])
                                            ->update(['quantity' => $quantity]);

                                        }

                                            // insert order into product columns by iterating over product table


                                            $messageCode = 'success';
                                            $messageText = 'נוצרה בהצלחה';






                }
   //   }else{
   //  $messageCode = 'error';
   //  $messageText = ' ריק, נא הזן מוצר אחד לפחות ';
   //  return redirect()->route('returns.show', [$newDateformat])->with($messageCode, $messageText);
   // }


       return redirect()->route('returns.show', [$newDateformat])->with($messageCode, $messageText);




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
