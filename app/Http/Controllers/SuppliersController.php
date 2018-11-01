<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use App\Product;
use App\Price;
use App\Order;
use App\orderItem;
use App\MissingProduct;
use App\MissingProductItem;
use Carbon\Carbon;
use PDF;
use App\MissingReport;


class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::paginate(10);
        return view('pages.suppliers')->with('suppliers', $suppliers);
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
        $validatedData = $request->validate([

            'email' => 'required|unique:suppliers,email'

        ]);

        $supplier = new Supplier;
        $supplier->name = $request->input("supplierName");
        $supplier->email = $request->input("email");
        $supplier->number = $request->input("number");
        $supplier->address = $request->input("address");
        $supplier->city = $request->input("city");
        $supplier->save();

        return redirect ('/suppliers')->with('success', 'ספק נוצרה בהצלחה');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
      $totalDebtToSupplier = '';
        $totalsArray = [];
        $orderTotalsArray = [];
       if($request->all() == []){
        date_default_timezone_set("Asia/Jerusalem");
        $from_date = date('Y-m-01');
         $to_date  = date('Y-m-t');

       }else{
           $from_date = Carbon::parse( $request->input('from_date'))->format('Y-m-d');
           $to_date = Carbon::parse( $request->input('to_date'))->format('Y-m-d');


       }

       $currentMonthOrders = Order::whereBetween('date',[$from_date,$to_date])
       ->get();

       $currentMonthMissing = MissingProduct::whereBetween('date',[$from_date,$to_date])
        ->get();
  $supplierProducts =   Product::where('supplier_id', $id)->where('active',1)->pluck('id')->toArray();

foreach ($currentMonthMissing as $missingDay) {
    $dayTotals = [];
    $missingItems =  $missingDay->missingItems()->whereIn('product_id',$supplierProducts)->get();
    foreach ($missingItems as $missingItem) {
       $dayTotals[$missingItem->product_id] = $missingItem->quantity * $missingItem->current_price;

    }
    $totalsArray[$missingDay->id] = array_sum($dayTotals);

}
$totalMissingCost = array_sum($totalsArray);

              $supplier = Supplier::find($id);
              $products = $supplier->products()->where('active', 1)->get();

              foreach ($currentMonthOrders as $order) {
                $allProductAmount = [];
              $orders =   $order->orderItems()->whereIn('product_id',$supplierProducts)->get();
                  foreach($orders as $orderItem){

                    $allProductAmount[$orderItem->product_id] = $orderItem->quantity * $orderItem->c_supplier_price;
                  }

               $orderTotalsArray[$order->id] = array_sum($allProductAmount);

              }



  $totalDebtToSupplier = array_sum($orderTotalsArray)  ;


      $data = array(
           'totalMissingCost' => $totalMissingCost,
           'from_date' => Carbon::parse( $from_date)->format('d-m-Y'),
           'to_date' => Carbon::parse( $to_date)->format('d-m-Y'),
           'totalDebtToSupplier' => $totalDebtToSupplier,
           'supplier' => $supplier,
           'products' =>$products
       );
       return view('suppliers.supplierShow')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::find($id);
       $products = $supplier->products()->get();
       $data = array(
           'supplier' => $supplier,
           'products' =>$products
       );
        return view('suppliers.supplierEdit')->with('data', $data);
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

        $validatedData = $request->validate([

            'supplierEmail' => 'required|unique:suppliers,email'

        ]);
        Supplier::where([
            'id' => $id
        ])->update(['name' => $request->input('supplierName'),
                     'number' => $request->input('supplierNumber'),
                    'email' => $request->input('supplierEmail'),
                    'city' => $request->input('supplierCity'),
                    'address' => $request->input('supplierAddress'),
                    ]);
                    return redirect()->route('suppliers.show', [$id])->with('success','עודכן');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $supplier =  Supplier::find($id);
       $supplier->delete();
       $products =  $supplier->products()->get();
        foreach($products as $product){
            Product::find($product->id)->update(['active' => 0 ]);
            $price = Price::where('product_id' ,$product->id)->update(['active' => 0]);
        }
        return redirect()->route('suppliers.index')->with('success','נמחקה; ');
    }

    public function missingProductsReport(Request $request, $supplier_id)
    {

        $supplier = Supplier::find($supplier_id);
        $from_date = Carbon::parse($request->input('from_date'))->format('Y-m-d');
        $to_date = Carbon::parse($request->input('to_date'))->format('Y-m-d');

         $reportExist = MissingReport::where(['supplier_id'=> $supplier->id,
                                                 'from_date' => $from_date,
                                                 'to_date'=>$to_date])->first();

           if ($reportExist == "") {
            $missingProducts = [];
            $missingItem = [];
            $orders = [];
            $orderSums = [];
            $missingSums = [];
            $missingCosts =[];
            $ordersCost =[];
            $names = [];
              $currentMonthOrdersIds = Order::whereBetween('date',[$from_date,$to_date])->pluck('id')->toArray();
             $productIds =  orderItem::whereIn('order_id',$currentMonthOrdersIds)->pluck('product_id')->toArray();
              $allProductsInOrders =  array_unique($productIds);
            $productsInOrders =  Product::whereIn('id',$allProductsInOrders)->where('supplier_id',$supplier_id)->pluck('id')->toArray();

              if($productsInOrders == []){
                  return redirect()->route('suppliers.show',$supplier->id)->with('error','לא נמצאו הזמנות בתאריכים אלו');
}
               foreach ($productsInOrders as $key => $productId) {
                  $names[Product::find($productId)->name] = $productId;
                  }

               $currentMonthMissing = MissingProduct::whereBetween('date',[$from_date,$to_date])->get();

               foreach ($currentMonthMissing as $missing) {
                  $quantity = 0;
                  foreach ($productsInOrders as $key => $productId) {

                      $quantity = 0;
                      if(!$missing->missingItems()->where('product_id' , $productId)->first() == ''){
                    $missingItem =     $missing->missingItems()->where('product_id' , $productId)->first();

                      $missingProducts[Carbon::parse($missing->date)->format('d-m-Y')][$productId] =  $missingItem->quantity;
                      $missingCosts[Carbon::parse($missing->date)->format('d-m-Y')][$productId] =  $missingItem->quantity *  $missingItem->current_price ;
                      }
                  }



              }

            //  return $missingCosts;

              //return $missingProducts;
               $currentMonthOrders = Order::whereBetween('date',[$from_date,$to_date])->get();
               //$monthOrdersIds = Order::whereBetween('date',[$from_date,$to_date])->pluck('id')->toArray();
              foreach ($currentMonthOrders as $order) {
                $monthOrdersIds = Order::where('date',$order->date)->pluck('id')->toArray();
                  foreach ($productsInOrders as $key => $productId) {

                      $quantity = 0;
                      if(!$order->orderItems()->where('product_id' , $productId)->first() == ''){
                       $orderItem =  $order->orderItems()->where('product_id' , $productId)->first();

                      }
                      $quantity = orderItem::whereIn('order_id', $monthOrdersIds)->where('product_id',$productId)->sum('quantity');
                      $orders[Carbon::parse($order->date)->format('d-m-Y')][$productId] = $quantity;
                    $ordersCost[Carbon::parse($order->date)->format('d-m-Y')][$productId] = $quantity * $orderItem->c_supplier_price;
                  }



              }
              foreach ($productsInOrders as $key => $productId) {
                  $missingSums[$productId] =  array_sum(array_column($missingProducts,$productId));
                 $orderSums[$productId] =  array_sum(array_column($orders,$productId));
          }

          foreach ($productsInOrders as $key => $productId) {
        $totalmProductsCosts[$productId] =  array_sum(array_column($missingCosts,$productId));
           $totaloProductsCosts[$productId] =  array_sum(array_column($ordersCost,$productId));
    }


          $data = array(
                'totalmProductsCosts' => $totalmProductsCosts,
               'totaloProductsCosts' => $totaloProductsCosts,
                  'from_date' => $from_date,
                  'to_date' => $to_date,
                  'supplier' => $supplier,
                  'orderSums' => $orderSums,
                  'missingSums' => $missingSums,
                  'missingProducts' => $missingProducts,
                  'orders' => $orders,
                  'names' => $names
              );
              //return $data;
          $pdfData =   $this->pdfWrapper($data, $supplier,$from_date,$to_date);
              $mpdf = new \Mpdf\Mpdf();
              $mpdf = PDF::loadView('missingProducts.missingProductsPdf', compact('pdfData'));
              $mpdf->save( storage_path('app/public/missingReportsPdf/mReport'.$from_date. '~~' . $to_date .'.pdf')  );



            //  return $orders;
              return view('missingProducts.missingProductsReport')->with('data',$data)->with(['from_date' => $from_date,
                                                                                               'to_date' => $to_date ]);
           }else{

            return redirect()->route('reports.show',$reportExist->id)->with('error','דו"ח כבר קיימת בתאריכים אלו');
           }



    }


    public function pdfWrapper($data,$supplier,$from_date,$to_date){
        ///initialize
         $oSumPages = [];
             $mSumPages = [];
             $mPages = [];
             $orderspages = [];
             $namespages = [];
             $mCostPages = [];
             $oCostPages = [];



        $amountToDisplay = 7;
       // names
        $pageNum = 1;
        foreach ($data['names'] as $name => $id) {
            $namespages[$pageNum][$name] = $id;
            if(count($namespages[$pageNum]) == $amountToDisplay){
                $pageNum++;
            }
        }

        foreach ($data['orders'] as $date => $array) {
            $orderspageNum = 1;
            foreach ($array as $id => $quantity) {
                $orderspages[$date][$orderspageNum][$id] = $quantity;
                if(count( $orderspages[$date][$orderspageNum]) == $amountToDisplay){
                    $orderspageNum++;
                }
            }

        }


        foreach ($data['missingProducts'] as $date => $array) {
            $mPageNum = 1;
            foreach ($array as $id => $quantity) {
                $mPages[$date][$mPageNum][$id] = $quantity;
                if(count( $mPages[$date][$mPageNum]) == $amountToDisplay){
                    $mPageNum++;
                }
            }

        }

        $mSumPageNum = 1;
        foreach ($data['missingSums'] as $id => $quantity) {
            $mSumPages[$mSumPageNum][$id] = $quantity;
            if(count($mSumPages[$mSumPageNum]) == $amountToDisplay){
                $mSumPageNum++;
            }
        }

        $oSumPageNum = 1;
        foreach ($data['orderSums'] as $id => $quantity) {
            $oSumPages[$oSumPageNum][$id] = $quantity;
            if(count($oSumPages[$oSumPageNum]) == $amountToDisplay){
                $oSumPageNum++;
            }
        }
        $oCostsPageNum = 1;
        foreach ($data['totaloProductsCosts'] as $id => $quantity) {
            $oCostPages[$oCostsPageNum][$id] = $quantity;
            if(count($oCostPages[$oCostsPageNum]) == $amountToDisplay){
                $oCostsPageNum++;
            }
        }
        $mCostsPageNum = 1;
        foreach ($data['totalmProductsCosts'] as $id => $quantity) {
            $mCostPages[$mCostsPageNum][$id] = $quantity;
            if(count($mCostPages[$mCostsPageNum]) == $amountToDisplay){
                $mCostsPageNum++;
            }
        }

        $pdfData = array(
            'oSumCosts' => array_sum($data['totaloProductsCosts']),
            'mSumCosts' => array_sum($data['totalmProductsCosts']),
            'totaloProductsCosts' => $oCostPages,
            'totalmProductsCosts' => $mCostPages,
            'supplier' => $supplier,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'orderSums' => $oSumPages,
            'missingSums' => $mSumPages,
            'missingProducts' => $mPages,
            'orders' => $orderspages,
            'names' => $namespages
        );
            return $pdfData;
    }






}
