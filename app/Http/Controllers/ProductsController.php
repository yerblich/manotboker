<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Price;
use App\Client;
use Schema;
use App\Supplier;
use Utils;
class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplierList = [];
        $products = Product::where('active', 1)->get();
        $LiveSearchArray =  Product::where('active', 1)->pluck('name')->toArray();
     $suppliers = Supplier::all();
     if(!empty($suppliers)){
        foreach($suppliers as $supplier){
            $supplierList[$supplier->id] = $supplier->name;
        }
     }else{
         $supplierList = [];
     }

     //return $supplierList;
        return view('pages.products')->with('products', $products)->with('suppliers', $supplierList)
        ->with('LiveSearchArray', $LiveSearchArray);
    }

    public function search(Request $request)
    {
       $product =   Product::where('name', $request->input('search'))->first();
      if($product == ''){
        return redirect()->route('products.index')->with('error', $request->input('search')." Does not Exist");
      }
        return redirect()->route('products.show',$product->id);
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
      $supplierId =  $request->input("supplier");
     $type =  $request->input("type");
     $weight =  $request->input("weight");
     $price =  $request->input("price");
     $units =  $request->input("units");
     $clients =  Client::orderBy('route', 'asc')->get();
      $supplierName = Supplier::find($request->input("supplier"))->name;

      $productFix =   Utils:: prefix_product($supplierName, $request->input("productName"),$type);

     $exist = Product::where('name', $productFix )->first();
       if($exist == ''){

        $product = new Product;

        $product->name = $productFix;
        $product->supplier_id =  $supplierId;
        $product->type = $type;
        $product->supplier_price = $price;
        $product->weight = $weight;
        $product->units = $units;
        $product->save();

        foreach($clients as $client){
            $price =  new Price;
            $price->client_id = $client->id;
            $price->product_id = $product->id;
            $price->price = 0;
            $price->save();

        }
        // Schema::table('orders', function($table) use($product)
        //     {
        //         $str = (string) $product->id;
        //         $table->integer($str)->nullable();
        //     });
        //     Schema::table('product_returns', function($table) use($product)
        //     {
        //         $table->integer($product->id)->nullable();
        //     });
        //     Schema::table('prices', function($table) use($product)
        //     {
        //         $table->integer($product->id)->nullable()->default(0)	;
        //     });
            $messageType = 'success';
            $messageText = ' הוספת מוצר בהצלחה, נא למלא מחירים למוצרים ';
       }else{
        if($exist->active == 0 ){
           // return $supplierId;
         Product::find($exist->id)->update([ 'supplier_id' => $supplierId, 'active' => 1]);
            Price::where('product_id' ,$exist->id)->update(['active' => 1]);
            $messageType = 'success';
            $messageText = '  הוספת מוצר בהצלחה, נא למלא מחירים למוצרים ';
        }else{
            $messageType = 'error';
            $messageText = 'מוצר כבר קיימת';
        }

       }


        return redirect ('/products')->with($messageType, $messageText);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $product =  Product::find($id);

     $supplier = $product->supplier()->first();
     if($product->type == 1){
        $type = "יומי";
    }elseif($product->type == 0){
        $type = "שבת";
    }else {
      $type = "אמריקאי";
    }
        $data = array(
            'id' => $id,
            'name' => $product->name,
            'imageName' => $product->image,
            'supplier' => $supplier->name,
            'weight' => $product->weight,
            'units' => $product->units,
            'supplierPrice' => $product->supplier_price,
            'type' => $type

        );
        return view('products.productsShow')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product =  Product::find($id);
        $supplier = $product->supplier()->first();




           $data = array(
               'id' => $id,
               'name' => $product->name,
               'imageName' => $product->image,
               'supplier' => $supplier->name,
               'weight' => $product->weight,
                'units' => $product->units,
               'supplierPrice' => $product->supplier_price,
               'type' => $product->type
           );

        return view('products.productEdit')->with('data', $data);
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

      $product =  Product::find($id);
      $type = $request->input('type');
      if($request->input('productName') !== null){
        $productName = $request->input('productName');
      }else{
        $productName = $product->name;
      }
      $supplier_id =   $product->supplier_id;
      $supplierName =  Supplier::find($supplier_id)->name;
      $productName  =   Utils::prefix_product($supplierName, $productName,$type);
     $duplicate =   Product::where('name',$productName)->first();
    if($productName !== $product->name &&  $duplicate !== null){
        return redirect()->route('products.edit',$product->id)->with('error','מוצר קיים, לא ניתן להשתמש בשם זה');
    }

     if($request->hasFile('product_image')){
        $extension = $request->file('product_image')->getClientOriginalExtension();
        $filenameToStore = $productName.'.'.$extension;
        $path = $request->file('product_image')->storeAs("public/productImages/$supplierName",$filenameToStore);
     }else{
        $filenameToStore = "noimage.jpg";
     }




         $product->update([
             'name' => $productName,
             'weight' => $request->input('weight'),
             'units' => $request->input('units'),
             'supplier_price' => $request->input('supplierPrice'),
             'type' => $request->input('type')


         ]);
         if($request->hasFile('product_image')){
           $product->update([ 'image' =>  $filenameToStore]);
            }

         return redirect()->route('products.show',$product->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id)->update(['active' => 0]);
        $price = Price::where('product_id' ,$id)->update(['active' => 0]);

        return redirect ('/products')->with('success', 'מוצר נמחקה');
    }
}
