<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use App\Supplier;
use App\Product;
use App\Order;
use App\orderItem;
use Carbon\Carbon;
use App\MissingProduct;
use App\MissingProductItem;
use App\MissingReport;
class MissingProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $supplier =  Supplier::find(Input::get('supplierId'));
        $missingProducts = $supplier->missingProducts()->get();
        $datesWithMissing =[];
        foreach ($missingProducts as $missingProduct) {
         // $date =   Carbon::parse($missingProduct->date)->format('m-Y');
            $datesWithMissing[  Carbon::parse($missingProduct->date)->format('m-Y')][] = $missingProduct;

        }
       // return $datesWithMissing;
        return view('missingProducts.indexMissingProducts')->with(['supplier'=>$supplier,
                                                                'datesWithMissing'=> $datesWithMissing]);
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request )
    {
      // $request ;
       $supplierId =$request->input('supplierId');
       $date =   Carbon::parse($request->input('date'))->format('Y-m-d');
       $exist = MissingProduct::where(['supplier_id'=> $supplierId, 'date' => $date])->first();
       if($exist == ''){
       $allProducts = [];

      $orders =   Order::where('date',$date)->get();
        if(!$orders->count()){
            return redirect()->route('missingProducts.index',['supplierId' =>$supplierId])->with('error','לא נמצאו הזמנות בתאריך הזה ');
        }
       // return $orders;
        foreach ($orders as $order) {

       $currentOrderProducts = $order->orderItems()->pluck('product_id')->toArray() ;
          array_push($allProducts,$currentOrderProducts);
          //convert array of arrays into single array
   $result =  call_user_func_array("array_merge", $allProducts);
        }
        //delete duplicate products
      $productsInOrders =  array_unique($result);

        $products = [];
       $supplier =  Supplier::find($supplierId);
      $allProducts = $supplier->products()->whereIn('id', $productsInOrders)->where('active', 1)->get();


        foreach($allProducts as $product){
            if( $product->type == 1){
                $products['daily'][$product->id] = $product->name;
            }else{
                $products['shabbos'][$product->id] = $product->name;
            }

        }

        if($products == []){
            return redirect()->route('missingProducts.index',['supplierId' =>$supplierId])->with('error','לא נמצאו הזמנות מהספק הנבחר בתאריך הזה');
        }

           $data = array(
            'date' => $request->input('date'),
            'supplier' => $supplier,
            'products' => $products
        );
     } else{
        $messageText = "דוח כבר קיימת";
        $messageType = "error";
        return redirect()->route('missingProducts.index',['supplierId' =>$exist->supplier_id])->with($messageType,$messageText);
     }
      //  return $data;
        return view('missingProducts.createMissingProducts')->with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $supplierId = $request->input('supplier_id');
      $date =  Carbon::parse($request->input('date'))->format('Y-m-d');

        $missingProduct = new MissingProduct;
        $missingProduct->supplier_id = $supplierId;
        $missingProduct->date = $date;
        $missingProduct->save();

        $missingProducts = array_filter($request->all(), function($key) {
            return is_numeric($key);
          }, ARRAY_FILTER_USE_KEY);

          foreach ($missingProducts as $product_id => $quantity) {
            $missingProductItem =  new MissingProductItem;
            $missingProductItem->missing_product_id = $missingProduct->id;
            $missingProductItem->product_id = $product_id;
            $missingProductItem->quantity = $quantity;
            $missingProductItem->current_price = Product::find($product_id)->supplier_price;
            $missingProductItem->save();

          }
          $messageText = "updated";
          $messageType = "success";


       return redirect()->route('missingProducts.show',[$missingProduct->id])->with($messageType,$messageText);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $missingProducts = [];
       $missingProduct =  MissingProduct::find($id);
       $supplier = Supplier::find($missingProduct->supplier_id);
    $missingItems =  $missingProduct->missingItems()->get();
      foreach ($missingItems as $missingItem) {

        $missingProducts[Product::find($missingItem->product_id)->name] = $missingItem->quantity;
      }
      $data = array(
          'supplier' => $supplier,
          'id' => $missingProduct->id,
          'date' => Carbon::parse($missingProduct->date)->format('d-m-Y'),
          'missingProducts' =>  $missingProducts
      );
      return view('missingProducts.showMissingProducts')->with('data',$data);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $missingProducts = [];
       $missingProduct =  MissingProduct::find($id);
    $missingItems =  $missingProduct->missingItems()->get();
      foreach ($missingItems as $missingItem) {
        $missingProductsIds[Product::find($missingItem->product_id)->name] = $missingItem->product_id;
        $missingProducts[Product::find($missingItem->product_id)->name] = $missingItem->quantity;
      }
      $data = array(
          'id' => $missingProduct->id,
          'date' => Carbon::parse($missingProduct->date)->format('d-m-Y'),
          'missingProducts' =>  $missingProducts,
          'missingProductsIds' => $missingProductsIds
      );

      return view('missingProducts.editMissingProducts')->with('data',$data);
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


      $missingProducts = array_filter($request->all(), function($key) {
            return is_numeric($key);
          }, ARRAY_FILTER_USE_KEY);

          foreach ($missingProducts as $product_id => $quantity) {
              MissingProductItem::where(['product_id' => $product_id, 'missing_product_id' => $id])
              ->update(['quantity' => $quantity]);

            }
            return redirect()->route('missingProducts.show',$id)->with('success','דוח עודכן בהצלחה ');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $supplierId =  MissingProduct::find($id)->supplier_id;
        MissingProductItem::where('missing_product_id', $id)->delete();
        MissingProduct::find($id)->delete();
        return redirect()->route('missingProducts.index',['supplierId' => $supplierId ])->with('success','דוח נמחקה');
    }
}
