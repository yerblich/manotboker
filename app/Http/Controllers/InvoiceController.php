<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\Client;
use App\Product;
use App\Price;
use App\orderItem;
use App\returnItem;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use PDF;
use DB;
use Input;
use App\Charts\ClientChart;
use Lava;
use Khill\Lavacharts\Lavacharts;
use Redirect;
use Schema;
use App\Order;
use Illuminate\Support\Facades\Storage;
use File;
use InvoiceFactory;
//use Illuminate\Support\Facades\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//         $allInvoices =   Invoice::all();
//    return $allInvoices;
        $clients = Client::orderBy('route', 'asc')->get();
        $allInvoices =[];
        foreach($clients as $client){
            $allInvoices[$client->name] = $client->invoices()->get();
        }
//return $invoices;
        return view('pages.invoices')->with('invoices', $allInvoices);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {


     $invoiceId = InvoiceFactory::getCurrentIncrement();
        //get client ids and dates
    $req =  array('data' => json_decode($request->data));
   $clientId =  $req['data']->client->id;
  $from_date = Carbon::parse($req['data']->from_date)->format('Y-m-d') ;
   $to_date =  Carbon::parse($req['data']->to_date)->format('Y-m-d') ;

 //check if invoice exists // this is for if he creates another invoice of the same
 // dates and wants to overwrite
   $invoice_exists = Invoice::where(['client_id' => $clientId, 'from_date' => $from_date, 'to_date' => $to_date])->first();
 if($invoice_exists !== null ){

return redirect()->route('invoices.show', [$invoice_exists->id])->with('error','חשבונית כבר קיימת');

 }
   $data =  InvoiceFactory::generateInvoice($clientId,$from_date,$to_date,$invoiceId);


  //return $data;

  $pdf = PDF::loadView('clients.pdfInvoice', compact('data'))->save( storage_path('app/public/pdfInvoices/invoicePreview.pdf')  );
         return view('clients.createInvoice')->with('data', $data)->with(['from_date' => $from_date, 'to_date' => $to_date]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $clientId)
    {



        $data =  json_decode($request->input('data'),TRUE);
  $client = Client::find($clientId);

     $from_date = Carbon::parse($data['from_date'])->format('Y-m-d') ;
     $to_date =  Carbon::parse($data['to_date'])->format('Y-m-d') ;
        $debt = $data['totalToPay'];

      $exist =   Invoice::where(['client_id' => $clientId,'from_date' => $from_date , 'to_date' => $to_date])->first();
    // return $clientId;

    if($exist == ''){



        $invoice = new Invoice;
        $invoice->client_id = $clientId;
        $invoice->from_date = $from_date;
        $invoice->to_date = $to_date;
        $invoice->debt = $debt;
          $invoice->printed = true;

        $invoice->paid = 0;

        $invoice->save();

  	$path =  storage_path('app/public/pdfInvoices/'.$client->name) ;

           if (!File::exists($path))
            {
              File::makeDirectory($path, $mode = 0777, true, true);
            }
            $data['invoiceId'] = $invoice->id;
            $data['isOriginal'] = false;
        $pdf = PDF::loadView('clients.pdfInvoice', compact('data'))
        ->save( storage_path('app/public/pdfInvoices/'.$client->name.'/invoice'.$invoice->id.'.pdf')  );



       $this->updateBalance($clientId);






        $messageType = "success";
        $messageText = "חשבונית נוצרה";
        $sent = $request->input('send');
        if(isset($sent)){
            $data['invoiceId'] = $invoice->id;
            $data['client'] = $client;
        Mail::send('orders.supplierEmail', $data, function($message) use ($data){

            $message->from('sales@manotboker.com');
            $message->to($data['client']->email);
            $message->subject('invoice '. $data['from_date']. 'To'.$data['to_date'] );
            $message->attach(  url('storage/pdfInvoices/'.$data['client']->name.'/invoice'.$data['invoiceId'].'.pdf')  );
        });
        $messageText = "חשבון נשלחה";
    $invoice->update(['sent' => 1]);
    }

       $invoiceId = $invoice->id;
      }else{

        $messageType = "success";
        $messageText = "חשבונית כבר קיימת";
         $invoiceId = $exist->id;
      }

      return redirect()->route('invoices.show', [$invoiceId])->with($messageType,$messageText);

    }

    public function pdfDownload(Request $request)
    {

        $mpdf = new \Mpdf\Mpdf();
       // $mpdf->AddFontDirectory();
        // $mpdf->WriteHTML('<h1>יחחיחי</h1>');
        // $mpdf->Output();
     $data = array('pdf' => json_decode($request->pdf));

         $from_date =  $data['pdf']->from_date;
         $to_date =  $data['pdf']->to_date;
       $mpdf = PDF::loadView('clients.clientPdfInvoice', compact('data'));
    // //    $pdf->setPaper('A4', 'landscape');

     return $mpdf->stream('invoice'.$from_date.'-'.$to_date.'.pdf');
    }

    public function pdfSend(Request $request)
    {



         // $date = strtotime($data['pdf']->date);
         $data['from_date'] = Carbon::parse($request->from_date)->format('Y-m-d') ;
         $data['to_date'] =  Carbon::parse($request->to_date)->format('Y-m-d');
        $data['invoice_id'] = $request->invoice_id;

        //  $pdf = PDF::loadView('clients.clientPdfInvoice', compact('data'))->save( public_path('pdfInvoices/invoice'.$from_date.'-'.$to_date.'.pdf')  );
       $id =   $request->client_id;
         $data['client'] =  Client::find($id);
       // return  $data['supplier']->email;
     //  foreach($data['suppliers'] as $data['supplier']){

        Mail::send('orders.supplierEmail', $data, function($message) use ($data){

            $message->from('sales@manotboker.com');
            $message->to($data['client']->email);
            $message->subject('invoice '. $data['from_date']. 'To'.$data['to_date'] );
            $message->attach(  asset('storage/pdfInvoices/'.$data['client']->name.'/invoice'. $data['invoice_id'].'.pdf')  );
        });
       //}
       $findInvoiceID = Invoice::where([
        'client_id' => $data['client']->id,
        'from_date' => $data['from_date'],
        'to_date' => $data['to_date']   ])->first();
      $id =   $findInvoiceID->id;

      $findInvoiceID->update(['sent' => 1]);
         return redirect()->route('invoices.show', [$data['invoice_id']])->with('success', 'Pdf נשלחה');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


    $invoice = Invoice::find($id);
    $from_date = $invoice->from_date;
    $to_date = $invoice->to_date;
    $client = Client::find($invoice->client_id);

        $sent = $invoice->sent;
      $paid = $invoice->paid;
      $debt = $invoice->debt;






    //   $data = array(
    //       'paid' => $invoice->paid,
    //     'client' => $client,
    //     'orders' => $orders,
    //     'productNames' =>  $productNames,
    //     'products'=> $products,
    //     'from_date' => Carbon::parse($from_date)->format('d-m-Y'),
    //     'to_date' => Carbon::parse($to_date)->format('d-m-Y'),
    //     'invoiceInfo' => $invoiceInfo,
    //     'totalToPay' => $totalToPay
    // );

         return view('clients.clientInvoice')->with(['paid' => $paid,
                                                     'debt' => $debt,
                                                    'sent' => $sent,
                                                    'from_date' => $from_date,
                                                     'to_date' => $to_date,
                                                      'client' => $client,
                                                      'invoice' => $invoice]);
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
    public function update(Request $request, $invoiceId)
    {
      // return $invoiceId;
        $paid  = $request->input('paid');
        if(isset($paid )){
               $amountPaid =  $request->input('amountPaid');

             $from_date =  Carbon::parse( $request->from_date)->format('Y-m-d');
             $to_date = Carbon::parse( $request->to_date)->format('Y-m-d');


            //$invoiceDebt = $data['data']->totalToPay -  $amountPaid ;
    $invoice =  Invoice::find($invoiceId);
     $invoice->update(['paid' => $amountPaid]);


      $this->updateBalance($invoice->client_id);
        }

        return redirect()->route('invoices.show',[$invoiceId])->with('success', 'עודכן בהצלחה');

    }//

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $invoice =  Invoice::find($id);
      $clientId = $invoice->client_id;
      $invoice->delete();
     $this->updateBalance($clientId);

       return redirect()->route('invoices.index')->with('success','חשבונית נמחקה');
    }



public function MassInvoice(){
    // $clients = Client::all();
    // foreach($clients as $client){
    //     $clientsWithDuplicateInvoice = [];
    //     $invoice_exists = Invoice::where(['client_id' => $client->id, 'from_date' => $from_date, 'to_date' => $to_date])->first();
    //     if(!$invoice_exists == ''){
    //         $clientsWithDuplicateInvoice[$client->id] = true;

    //        $overWriteAlert = true;
    //     }
    // }
    // return $clientsWithDuplicateInvoice;
    $overWriteAlert = false;

    $data = array(
        'clients' => Client::all(),
        'overWriteAlert' => $overWriteAlert
    );
    //return $data;
    return view('invoices/massInvoice')->with('data',$data);
}
public function checkExistingInvoice(Request $request){

   $test =  response()->json(['from_date' => $request['from_date'], 'to_date' => $request['to_date'] ]);
    $e =  json_decode($test->content(),true);
   $from_date = Carbon::parse( $e['from_date'])->format('Y-m-d');
    $to_date = Carbon::parse( $e['to_date'])->format('Y-m-d');
    $invoice_exists = Invoice::where([ 'from_date' => $from_date, 'to_date' => $to_date])->first();
     if($invoice_exists == ''){
        return   response()->json(['from_date' => 'noduplicate']);
     }else{
        return   response()->json(['from_date' => 'duplicate']);
     }

}

public function generateMassInvoice(Request $request){



   $clients = array_keys(array_filter($request->all(), function($key) {
        return is_numeric($key);
      }, ARRAY_FILTER_USE_KEY));
      $from_date =  Carbon::parse( $request->from_date)->format('Y-m-d');
      $to_date = Carbon::parse( $request->to_date)->format('Y-m-d');
        $all = [];


          $invoiceId = InvoiceFactory::getCurrentIncrement();


     foreach ($clients as $clientId) {



        $invoice_exists = Invoice::where(['client_id' => $clientId, 'from_date' => $from_date, 'to_date' => $to_date])->first();
        if($invoice_exists !== null ){
        return redirect()->back()->with('error',' אחד או יותר חשבונית כבר קיימת ');
        }

      $client = Client::find($clientId);

        //get all orders between date of created invoice

        $data =  InvoiceFactory::generateInvoice($clientId,$from_date,$to_date,$invoiceId);


        $all[$client->name]['orders'] = $data['orders'];
        $all[$client->name]['invoiceInfo'] = $data['invoiceInfo'];
         $all[$client->name]['from_date'] = $data['from_date'];
         $all[$client->name]['to_date'] = $data['to_date'];
         $all[$client->name]['client'] = $client;
        $all[$client->name]['totalToPay'] =  $data['totalToPay'];
        $all[$client->name]['invoiceId'] =  $data['invoiceId'];
        $all[$client->name]['isOriginal'] =  $data['isOriginal'];
$invoiceId++;
     }
    // return $all;



  $pdf = PDF::loadView('invoices.pdfMassInvoice', compact('all'));
  // $pdf->SetProtection(['fill-forms'], '', 'pass');
  $pdf->save( storage_path('app/public/pdfInvoices/pdfMassInvoicePreview.pdf')  );
 return view('invoices.massInvoicePreview')->with('data',$all);
}



public function saveMassInvoice(Request $request){
    $all =  json_decode($request->input('data'),TRUE);

  foreach($all as $clientArray){
      if(!$clientArray['orders'] == ''){
      $data = $all[$clientArray['client']['name']];

        $from_date = Carbon::parse($clientArray['from_date'])->format('Y-m-d') ;
        $to_date =  Carbon::parse($clientArray['to_date'])->format('Y-m-d') ;
           $debt = $clientArray['totalToPay'];



           $exist =   Invoice::where(['client_id' => $clientArray['client']['id'],'from_date' => $from_date , 'to_date' => $to_date])->first();
           // return $clientId;

           if($exist == ''){
            $invoice = new Invoice;
            $invoice->client_id = $clientArray['client']['id'];
            $invoice->from_date = $from_date;
            $invoice->to_date = $to_date;
            $invoice->debt = $debt;
            $invoice->paid = 0;
            $invoice->printed = true;

            $invoice->save();
           }else{
            $exist->update(['debt' => $debt]);
            $invoice = $exist;
           }


          $path =  storage_path('app/public/pdfInvoices/'.$clientArray['client']['name']) ;

           if (!File::exists($path))
            {

              File::makeDirectory($path, $mode = 0777, true, true);
            }
            $data['invoiceId'] = $invoice->id;
              $data['isOriginal'] = false;
           $pdf = PDF::loadView('clients.pdfInvoice', compact('data'))
               ->save( storage_path('app/public/pdfInvoices/'.$clientArray['client']['name'].'/invoice'.$invoice->id.'.pdf')  );



          $this->updateBalance($clientArray['client']['id']);






           $messageType = "success";
           $messageText = "חשבונית נוצרה בהצלחה";
           $sent = $request->input('send');
           if(isset($sent)){
               $data['invoiceId'] = $invoice->id;
               $data['clientName'] = $clientArray['client']['name'];
           Mail::send('orders.supplierEmail', $data, function($message) use ($data){

               $message->from('sales@manotboker.com');
               $message->to($data['client']['email']);
               $message->subject('invoice '. $data['from_date']. 'To'.$data['to_date'] );
               $message->attach(  url('storage/pdfInvoices/'.$data['clientName'].'/invoice'.$data['invoiceId'].'.pdf')  );
           });
           $messageText = "חשבונית נשלחה";
           $invoice->update(['sent' => 1]);
       }
      }


  }



     return redirect()->route('invoices.index')->with($messageType,$messageText);

}
public function originalCopy(Request $request){

   $from_date = $request->input('from_date');
   $to_date = $request->input('to_date');

  $client = Client::find($request->input('client_id'));
  $invoiceId = $request->input('invoice_id');



  $orders = $client->orders()->whereBetween('date',[$from_date,$to_date])->get();
   //get ids of all orders
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




foreach($products_array as $product_id ){

   $name =   Product::find($product_id)->name;
    $totalToPayForProduct = $totalToPay[$product_id]['totalToPay'];
//    return $allCurrentPrices[$product_id];
   $invoiceInfo[$name] = array(
        'price' =>$allCurrentPrices[$product_id],
        'ordered' => $qtyOfItemOrdered[$product_id],
        'returns' => $qtyOfReturns[$product_id],
        'totalSold' => $qtyOfItemOrdered[$product_id] - $qtyOfReturns[$product_id],
        'totalToPayForProduct' => $totalToPayForProduct
    );

  //  $totalToPay[$product_id] = $totalToPayForProduct;

  }

$totalToPay =  array_sum(array_column($totalToPay, 'totalToPay'));
//     $invoice->update(['debt' =>  $totalToPay]);
//     $allDebt = Invoice::all()->pluck('debt')->toArray();
//     $allPaid = Invoice::all()->pluck('paid')->toArray();
//    $balance =  array_sum($allDebt)  - array_sum($allPaid);
//      if($balance < 0){
//         Client::find($invoice->client_id)->update(['credit' => abs($balance), 'debt' => 0]);

//      }elseif($balance > 0 ){
//         Client::find($invoice->client_id)->update(['debt' => $balance, 'credit' => 0]);

//      }else{
//         Client::find($invoice->client_id)->update(['debt' => 0, 'credit' => 0]);
//      }



$data = array(
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
$config = ['instanceConfigurator' => function($mpdf) {
    $mpdf->SetImportUse();
    $mpdf->percentSubset = 0;
    $search = array(
	'834'

);

$replacement = array(
	'835',

);
    $mpdf->OverWrite('invoices.originalCopyPdf', $search, $replacement, 'I');
}];



$pdf = PDF::loadView('invoices.originalCopyPdf', compact('data'), [], $config);

$pdf->stream( 'originalCopyPdf.pdf'  );



}

    public function updateBalance($clientId){
        $allDebt = Invoice::where('client_id' , $clientId)->pluck('debt')->toArray();
        $allPaid = Invoice::where('client_id' , $clientId)->pluck('paid')->toArray();
       $balance =  array_sum($allDebt)  - array_sum($allPaid);
         if($balance < 0){
            Client::find($clientId)->update(['credit' => abs($balance), 'debt' => 0]);

         }elseif($balance > 0 ){
            Client::find($clientId)->update(['debt' => $balance, 'credit' => 0]);

         }else{
            Client::find($clientId)->update(['debt' => 0, 'credit' => 0]);
         }
       }


}
