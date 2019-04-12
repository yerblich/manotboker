<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Price;
use App\Client;
use App\Product;
use DB;

class PricesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $currentPriceList = [];
        $allPrices = [];
      // $prices = Price::where('active', 1)->get();

        $products = Product::where('active', 1)->get();

        $clients =  Client::orderBy('name', 'asc')->get();

  //  $delete =   $products = Product::where('active', 0)->get();
  // foreach ($delete as $prodtodelete) {
  //   Price::where('product_id',$prodtodelete->id)->update(['active' => 0]);
  // }
  // return "done";
        foreach($clients as $client){

                    //$client->prices()->where('active', 1)->get();
       $priceList =  DB::table('prices')->select('product_id','price')->where(['client_id' => $client->id, 'active' => 1])->get();

         foreach($priceList as $product){
             $currentPriceList[$product->product_id] = $product->price;
         }
             $allPrices[$client->name] = $currentPriceList;
        }


       $data = array(
           'allPrices' => $allPrices,
           'clients' => $clients,
           'products' => $products
       );
       //return $data;
        return view('pages.prices')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       $products = Product::where('active', 1)->get();
        $clients =  Client::orderBy('route', 'asc')->get();
       foreach($clients as $client){

        //   return $client->prices();
    //        if(Price::where('client_id', $client->id)->first() == ""){
    //         $prices = new Price;
    //         $prices->client_id = $client->id;

    //         foreach($products as $product ){
    //        //$r = $name['name'];
    //          $prices->$product->id =  $request->input($client->id ."_". $product->id);
    //                     }

    //                     $prices->save();
    //    }else{
//$prices =      DB::table('prices')->where(['client_id' => $client->id, 'active' => 1])->get();

        foreach($products as $product){

            $priceItem  =    DB::table('prices')->where(['client_id' => $client->id , 'product_id' => $product->id])
            ->update(['price' => $request->input($client->name ."_". $product->id )]);

            //  $prices->update([$product->id =>  $request->input($client->name ."_". $product->id)]);
                         }




           }

      return redirect('prices')->with('success',"עודכן בהצלחה");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($blank)
    {

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
