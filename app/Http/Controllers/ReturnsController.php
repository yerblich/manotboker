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
        $allReturns =   ProductReturn::distinct()->get(['date']);
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
        
        date_default_timezone_set("Asia/Jerusalem");
        $currentDate =  date("d-m-Y");
        
           
           
       //get al clients 
     
    $allClients =  Client::orderBy('route', 'asc')->get();
   
   
// return $allClients;
   // get all products in order to create form 
   $products = Product::where('active' , 1)->get();
         $data = array(
        
        'products' =>  $products,
        'clientList' =>  $allClients,
       
        'currentDate' => $currentDate

      );
      
         
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
        $requestProducts = $request->all();
        unset($requestProducts['_token'],$requestProducts['date'],$requestProducts['parsha']);
      
     //validate date 
        $this->validate($request,[
            'date' => 'required',
             
        ]);
     
   
   // return $request->all();
    //  select first charachters of name attribute which corresponds to client id
      foreach($request->all() as $key => $value) { 
           $temp[]  = strtok( $key,  '_');
           
          }
         
          //remove duplicate
             $trimmedList = array_unique($temp);
      //remove everything but numbers
    $clients = array_filter($trimmedList, 'is_numeric');
   
  $products = Product::where('active', 1)->get();
   
    $date = strtotime($request->input("date"));
    $newDateformat = date('Y-m-d',$date);
    // check if all fields are empty , if not continue
   if(!max($requestProducts )== "") {
       // 
            foreach($clients as $client){
                            // check to see if a return exists with this client id and this date 
                                $returnCheck = ProductReturn::where([
                                    'client_id' => $client,
                                    'date' => $newDateformat
                            ])->first();
                            //select return of current client 
                            foreach($products as $product ){
                                $client_return[$product->id] =  $request->input($client ."_". $product->id);
                            }
                           
                        // if the max of all the return = nothing it means no return was placed for this client so skip, otherwise continue
                            if( !max($client_return) == "" ){
                                // after we see order is not empty check if it already exists , if not ...
                                   
                                        //create order
                                       
                                            
                                            $date = strtotime($request->input("date"));
                                         $newDateformat = date('Y-m-d',$date); 
                                         
                                         
                                         $return = ProductReturn::where([
                                                'client_id' => $client,
                                                'date' => $newDateformat
                                        ])->first();
                                    
                                        foreach($client_return as $productId => $quantity){
                                           
                                            returnItem::where(['product_return_id' => $return->id, 'product_id' => $productId])
                                            ->update(['quantity' => $quantity]);
                                          
                                        }
                                            
                                            // insert order into product columns by iterating over product table 
                                           
                                            DB::table('orders')
                                            ->where([
                                                'client_id' => $client,
                                                'date' => $newDateformat])
                                                ->update(['product_return_id' => $return->id]);
                                            $messageCode = 'success';
                                            $messageText = 'נוצרה בהצלחה';
                                        
                                    
                                
                            }
            
            
                }
     }else{
    $messageCode = 'error';
    $messageText = ' ריק, נא הזן מוצר אחד לפחות ';
    return redirect()->route('returns.create', [$newDateformat])->with($messageCode, $messageText);
   }
       
       
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
        $orders =  Order::where('date',$returnDate)->pluck('id')->toArray();
      $orderItems =   orderItem::whereIn('order_id', $orders)->pluck('product_id')->toArray();  
     $allproducts = array_unique($orderItems);
      foreach($allproducts as $product){
          if(Product::find($product)->type == 0){
            array_push($productsArray['shabbos'] ,Product::find($product));
          }else{
            array_push($productsArray['daily'] ,Product::find($product));
          }
      }

        $allClients =  Client::orderBy('route', 'asc')->get();
      //  $clientList = [];
        //      loop through clients and create array of the orders from yeserdays order in order to fill the form 
      //  if client does not have any orders a blank array will return     
      $resultArray = [];
      foreach($allClients as $client){
      
      
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
        
          $currentlist = $returnItems[$client->name];
        //initialize product array
       $product_array = [];
       //loop through products to create key of the array (so that all products come up , not just ordered items)
         foreach($productsArray as $orderType => $products ){
            $product_array[$orderType] =[];
            foreach($products as $product){
             $product_id = $product->id;
             //set default 0
        
             foreach($currentlist as $returnItem){
               
                     $quantity = $returnItem->quantity;
              
                 //else its stays at default 0 
             }
             //add item to product array 
             $product_array[$orderType][$product_id] = $quantity;
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
