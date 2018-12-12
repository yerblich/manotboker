<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Product;
use App\Price;
use App\Invoice;
use App\orderItem;
use App\returnItem;
use App\ProductReturn;
use App\Order;
use PDF;
use Input;
use Carbon\Carbon;
use App\Charts\ClientChart;
use Lava;
use Illuminate\Support\Facades\Mail;
use Khill\Lavacharts\Lavacharts;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $r = "P";
        $clients =  Client::orderBy('route', 'asc')->get();
        return view('pages.clients')->with('clients', $clients);
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

        // $validatedData = $request->validate([
        //
        //     'email' => 'required|unique:clients,email'
        //
        // ]);

        $clientName = str_replace(' ', '_', $request->input("clientName"));
        $client = new Client;

        $client->name = $clientName;
        $client->email = $request->input("email");
        $client->city = $request->input("city");
        $client->number = $request->input("number");
        $client->address = $request->input("address");
        $client->save();
        Client::find($client->id)->update(['route' =>$client->id]);
        $products = Product::all();

        foreach($products as $product){
            $price = new Price;
            $price->client_id = $client->id;
            $price->product_id = $product->id;
            $price->price = 0;

            $price->save();
        }

        return redirect ('/clients')->with('success', '  הוספת לקוח בהצלחה, נא למלא מחירים למוצרים ');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $client = Client::find($id);
       $invoices = $client->invoices()->orderBy('from_date' ,'desc')->get();

        // $orders = $client->orders()->orderBy('date','desc')->get();
        // $prices = $client->prices()->first();
        $products = Product::all();
        $productTotal = [];
        $productReturnTotal = [];
        $returnIds = $client->returns()->pluck('id')->toArray();
        $orderIds = $client->orders()->pluck('id')->toArray();
      foreach($products as $product){
        $productTotal[Product::find($product->id)->name] =   array_sum(orderItem::whereIn('order_id', $orderIds)->where('product_id', $product->id)->pluck('quantity')->toArray()) ;
        $productReturnTotal[Product::find($product->id)->name] =   array_sum(returnItem::whereIn('product_return_id', $returnIds)->where('product_id', $product->id)->pluck('quantity')->toArray()) ;
    }

        // $chart = new ClientChart;
        // $chart->dataset('singleChart', 'bar', [100, 65, 84, 45, 90, 100]);
        // $chart->options([
        //     'width' => 1000
        // ]);
        $data = Lava::DataTable();  // Lava::DataTable() if using Laravel

        $data->addStringColumn('string')
        ->addNumberColumn('Ordered')
        ->addNumberColumn('Returns')
        ->addNumberColumn('Total Sold');
         $data->addRoleColumn('string', 'tooltip');

            foreach($productTotal as $item => $amount)
            {
               // return $productReturnTotal[$item];
                $totalSold = ($amount-$productReturnTotal[$item]);
                $totalIncome = ($amount-$productReturnTotal[$item]);
                $data->addRow([$item, $amount, $productReturnTotal[$item],$totalSold,"Total Sold:$totalSold "] );
            }

       // return $productTotal;

         $chart =    Lava::ColumnChart('Finances', $data, [
            'title' => 'Sales',
            'width' => 1000,
            'height' => 300,
            'titleTextStyle' => [
                'color'    => '#eb6b2c',
                'fontSize' => 14
            ],

            'legend' => [
                'position' => 'top'
            ],
            'hAxis' => [
                'textStyle' => [
                    'fontSize'=> 10 // or the number you want
         ]
                ],


            'tooltip' => [
                'isHtml' => true
            ]

        ]);
      // return $chart->container() ;
  //    return $productReturnTotal;

       $data = array(
           'client' => $client,
          // 'orders' => $orders,
           'products'=> $products,
           'orderTotals' => $productTotal,
           'returnTotals' => $productReturnTotal,
        //   'chart' => $chart,
           'invoices' => $invoices
       );
//return $data;
        return view('clients.clientShow')->with('data', $data);
    }


    public function pdfDownload(Request $request)
    {

   $data = array('pdf' => json_decode($request->pdf));
     // return $data;
         $from_date =  $data['pdf']->from_date;
         $to_date =  $data['pdf']->to_date;
       $pdf = PDF::loadView('clients.clientPdfOrder', compact('data'));


        return $pdf->stream('order'.$from_date.'-'.$to_date.'.pdf');
    }
    public function pdfSend(Request $request)
    {


          $data = array('pdf' => json_decode($request->pdf));
         // $date = strtotime($data['pdf']->date);
          $from_date =  $data['pdf']->from_date;
         $to_date =  $data['pdf']->to_date;


          $pdf = PDF::loadView('clients.clientPdfOrder', compact('data'))->save( public_path('pdf/order'.$from_date.'-'.$to_date.'.pdf')  );
       $id =   $data['pdf']->client->id;
         $data['client'] =  Client::find($id);
       // return  $data['supplier']->email;
     //  foreach($data['suppliers'] as $data['supplier']){

        Mail::send('orders.supplierEmail', $data, function($message) use ($data){

            $message->from('yerblich@gmail.com');
            $message->to($data['client']->email);
            $message->subject('Detailed Order '. $data['pdf']->from_date. 'To'.$data['pdf']->to_date );
            $message->attach(  public_path('pdf/order'. $data['pdf']->from_date.'-'.$data['pdf']->to_date.'.pdf')  );
        });
       //}
       $data = array(
           'from_date' =>  $from_date ,
           'to_date' => $to_date
       );

      return redirect()->route('searchget',['data' => $data ,'id' => $id  ])->with('success' , '   נשלח בהצלחה Pdf');
    }


    public function search(Request $request ,$id){

      $oids   =  Order::all()->pluck('id')->toArray();

       returnItem::whereNotIn('product_return_id', $oids)->update(['test' => 'Delete']);
      returnItem::where('test', 'Delete')->delete();
        
  return      $from_date =  date('Y-m-d',strtotime($request->input('from_date')));
        $to_date = date('Y-m-d',strtotime($request->input('to_date'))) ;
        if ($request->isMethod('get')) {
            $from_date = date('Y-m-d',strtotime($request->data['from_date']));
            $to_date = date('Y-m-d',strtotime($request->data['to_date']));
        }
        $overWriteAlert = false;
        $invoice_exists = Invoice::where(['client_id' => $id, 'from_date' => $from_date, 'to_date' => $to_date])->first();
        if(!$invoice_exists == ''){


           $overWriteAlert = true;
        }
//         if (isset($input)){
//             $data = array(

//                 'from_date' => $from_date,
//                 'to_date' => $to_date
//             );
//             return redirect()->action('InvoiceController@store', ['id' => $id, 'data' => $data]);
//    }

        $client = Client::find($id);
        $orders = $client->orders()->whereBetween('date',[$from_date,$to_date])->get();
        if(!count($orders)){
            return redirect()->route('clients.show',$id)->with('error','לא נמצאו הזמנות לתאריכים אלה');
        }

        $products = Product::all();
        $productTotal = [];
        $productReturnTotal = [];
        $products_array = [];
        $orders_array = [];
        $returns_array = [];
        foreach($orders as $order){
            $allOrderItems[$order->id] = orderItem::where('order_id',$order->id)->get();
            foreach($allOrderItems as $id => $itemArray){
                foreach($itemArray as  $item){
                    array_push($products_array,$item->product_id) ;
                }





             }
        }
        $products_array =  array_unique($products_array);
        foreach($products_array as $productId){
            $productNames[$productId] = Product::find($productId)->name;
        }
        foreach($orders as $order){
          $return = $order->return()->first();

          $orderItems[$client->name] =  orderItem::where('order_id',$order->id)->get();
          if(!$return == ''){
            $returnItems[$client->name] =  returnItem::where('product_return_id',$return->id)->get();
            $returnItemlist = $returnItems[$client->name];
          }


          $orderItemlist = $orderItems[$client->name];

          //return $orderItems;
           //return $products_array;

            foreach($products_array as $product){
                $product_id = $product;
                $qty = 0;
                if(!$return == ''){
                foreach($returnItemlist as $returnItem){
                    return $returnItem;

                        // find the product inside the orderitems , if exists
                        if($returnItem->product_id == $product_id ){
                           //set to quantity
                            $qty = $returnItem->quantity;
                        }
                        }    //else its stays at default 0
                    }
                 $returns_array[$product_id] = $qty;




            }

         // return $returns_array;




            foreach($products_array as $product){

                $product_id = $product;
                $quantity = 0;

                foreach($orderItemlist as $orderItem){

                    if($orderItem->product_id == $product_id ){
                        //set to quantity

                         $quantity = $orderItem->quantity;

                     }
                     //else its stays at default 0

                     $order_array[$product_id] = $quantity;
                }
                    // find the product inside the orderitems , if exists




            }
          //  return array_sum($order_array);

            $allOrdersArray[$order->date->format('d-m-Y')]['orders'] = $order_array  ;

                $allOrdersArray[$order->date->format('d-m-Y')]['returns'] = $returns_array;





        }



    foreach($allOrdersArray as $test){
        foreach($test['returns'] as $id => $amount){
            $currentReturnsAmount[$id] = $amount;
        }

        $allReturnsAmounts[] = $currentReturnsAmount;

       foreach($test['orders'] as $id => $amount){
           $currentOrderAmount[$id] = $amount;
       }

       $allOrderAmounts[] = $currentOrderAmount;
    }
    foreach($products_array as $product){
        $productOrderTotals[$product] = ['orders'=> array_sum(array_column($allOrderAmounts,$product)),
                                         'returns' => array_sum(array_column($allReturnsAmounts,$product))  ] ;
    }
 //return $productNames;

       //  return $allOrdersArray;
    return   $data = array(
        'overWriteAlert' => $overWriteAlert,
           'client' => $client,
           'orders' => $orders,
           'productNames' =>  $productNames,
           'products'=> $products,
           'allOrders' => $allOrdersArray,
           'productOrderTotals' => $productOrderTotals,
           'from_date' => Carbon::parse($from_date)->format('d-m-Y'),
           'to_date' => Carbon::parse($to_date)->format('d-m-Y')
       );

       return view('clients.clientSearch')->with('data', $data);
    }

    // public function invoice(Request $data ,$id){
    //       $from_date = $data->data['from_date'];
    //     $to_date =  $data->data['to_date'];

    //     $products = Product::all();
    //     $client = Client::find($id);
    //     $productTotal = [];
    //     $productReturnTotal = [];
    //     $totalReturn = [];
    //     $orders = $client->orders()->whereBetween('date',[$from_date,$to_date])->get();
    //     $prices = $client->prices()->first();
    //     foreach($orders as $order){
    //         foreach($products as $product){
    //             $singleReturnArray[$product->id]  = object_get($order->return, "{$product->id}");
    //             $singleOrderArray[$product->id] = object_get($order, "{$product->id}");

    //         }
    //         $totalReturnsArray[] = $singleReturnArray;
    //       $totalOrdersArray[] = $singleOrderArray;
    //       foreach($products as $product){
    //         $productTotal[$product->id] = array_sum(array_column($totalOrdersArray, $product->id));
    //         $productReturnTotal[$product->id] = array_sum(array_column($totalReturnsArray, $product->id));
    //       }

    //     }
    //      //return   $productReturnTotal;
    //     foreach($productTotal as $id => $amount){

    //        $totalOrder[] =  $amount * $prices->$id;



    //       $totalReturn[] = $productReturnTotal[$id] * $prices->$id;


    //     }
    //     //return $totalReturn;
    //  $totalToPay = array_sum($totalOrder) - array_sum($totalReturn) ;
    //    $data = array(
    //     'client' => $client,
    //     'orders' => $orders,
    //     'products'=> $products,
    //     'orderTotals' => $productTotal,
    //     'returnTotals' => $productReturnTotal,
    //     'from_date' => $from_date,
    //     'to_date' => $to_date,
    //     'prices' => $prices,
    //     'totalToPay' => $totalToPay
    // );
    //      return view('clients.clientInvoice')->with('data', $data);
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client =  Client::find($id);

           $data = array(
               'id' => $id,
               'name' => $client->name,
               'email' => $client->email
           );
           return view('clients.clientEdit')->with('client', $client);
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

        // $validatedData = $request->validate([
        //
        //     'email' => 'required|unique:clients,email,'.$id
        //
        // ]);
        Client::where([
            'id' => $id
        ])->update(['name' => $request->input('clientName'),
                     'number' => $request->input('clientNumber'),
                    'email' => $request->input('email'),
                    'city' => $request->input('clientCity'),
                    'address' => $request->input('clientAddress'),
                    ]);
                    return redirect()->route('clients.show', [$id])->with('success','עודכן');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Client::find($id)->delete();
        Price::where('client_id',$id)->delete();
        return redirect()->route('clients.index')->with('success','לקוח נמחק');

    }
}
