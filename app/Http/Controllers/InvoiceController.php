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
use Utils;
use App\Charts\ClientChart;
use Lava;
use Khill\Lavacharts\Lavacharts;
use Redirect;
use Schema;
use App\Order;
use Illuminate\Support\Facades\Storage;
use File;
use InvoiceFactory;
use App\Jobs\sendEmailJob;
use App\Mail\sendEmail;
use App\PrevReturnItem;
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
        $clients = Client::orderBy('name', 'asc')->get();
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

      $invoiceType =  'invoice';
      if ($request->has('invoiceType')) {
        $invoiceType =  $request->input('invoiceType');

      }
      $request->flashOnly(['invoiceType','notes','discount','fee','discountType','feeType']);

      $info =  array(
        'source' => 'original',
        'notes' => $request->input('notes'),
        'discount' => $request->input('discount'),
        'discountType' => $request->input('discountType'),
        'fee' => $request->input('fee'),
        'feeType' => $request->input('feeType') ,
       );


     $invoiceNum = InvoiceFactory::getCurrentIncrement();
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

     $data =  InvoiceFactory::generateInvoice($clientId,$from_date,$to_date,$invoiceNum, $info);


  //return $data;

  $data['invoiceType'] =  $invoiceType;
  //$data['discount'] = $info['discount'];
//  $data['fee'] = $info['fee'];

  $pdf = PDF::loadView('clients.pdfInvoice', compact('data'))->save( storage_path('app/public/pdfInvoices/invoicePreview.pdf')  );
         return view('clients.createInvoice')->with('data', $data)->with(['from_date' => $from_date, 'to_date' => $to_date,'invoiceType'=>$invoiceType]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $clientId)
    {

//return $request;

        $data =  json_decode($request->input('data'),TRUE);
        $client = Client::find($clientId);


        $from_date = Carbon::parse($data['from_date'])->format('Y-m-d') ;
        $to_date =  Carbon::parse($data['to_date'])->format('Y-m-d') ;
          $debt = $data['grandTotal'] ;

          $exist =   Invoice::where(['client_id' => $clientId,'from_date' => $from_date , 'to_date' => $to_date])->first();
    // return $clientId;

      if($exist == ''){



        $invoice = new Invoice;
        $invoice->client_id = $clientId;
        $invoice->from_date = $from_date;
        $invoice->to_date = $to_date;
        $invoice->debt = $debt;
        $invoice->printed = true;


        $invoice->notes = $data['notes'];
        $invoice->documented_debt = $data['prevdebt'];
        $invoice->documented_credit = $data['prevcredit'];
        $invoice->discount = $data['discount'];
        $invoice->fee = $data['fee'];
        $invoice->discount_type = $data['discountType'];
        $invoice->fee_type = $data['feeType'];
        if ($request->input('invoiceType') == 'invoice') {
        $invoice->invoice_num = $data['invoiceNum'];
        }


        $invoice->paid = 0;


        $invoice->save();

  	$path =  storage_path('app/public/pdfInvoices/'.$client->id) ;

           if (!File::exists($path))
            {
              File::makeDirectory($path, $mode = 0777, true, true);
            }
            $data['invoiceId'] = $invoice->id;
            $data['isOriginal'] = false;
        $pdf = PDF::loadView('clients.pdfInvoice', compact('data'))
        ->save( storage_path('app/public/pdfInvoices/'.$client->id.'/invoice'.$invoice->id.'.pdf')  );



       Utils::updateBalance($clientId);






        $messageType = "success";
        $messageText = "חשבונית נוצרה";
        $sent = $request->input('send');
        if(isset($sent)){
            $data['invoiceId'] = $invoice->id;
            $data['client'] = $client;

            $contactInfo = $data['client'];
            $view = 'orders.supplierEmail';
            $attachmentUrl = url('storage/pdfInvoices/'.$data['client']->id.'/invoice'. $data['invoice_id'].'.pdf');
            $subject = 'invoice '. $data['from_date']. 'To'.$data['to_date'];

             dispatch(new sendEmailJob($contactInfo,$view,$subject,$attachmentUrl));

        // Mail::send('orders.supplierEmail', $data, function($message) use ($data){
        //
        //     $message->from('sales@manotboker.com');
        //     $message->to($data['client']->email);
        //     $message->subject('invoice '. $data['from_date']. 'To'.$data['to_date'] );
        //     $message->attach(  url('storage/pdfInvoices/'.$data['client']->id.'/invoice'.$data['invoiceId'].'.pdf')  );
        // });
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
     $contactInfo = $data['client'];
     $view = 'orders.supplierEmail';
     $attachmentUrl = url('storage/pdfInvoices/'.$data['client']->id.'/invoice'. $data['invoice_id'].'.pdf');
     $subject = 'invoice '. $data['from_date']. 'To'.$data['to_date'];

      dispatch(new sendEmailJob($contactInfo,$view,$subject,$attachmentUrl));
        // Mail::send('orders.supplierEmail', $data, function($message) use ($data){
        //
        //     $message->from('sales@manotboker.com');
        //     $message->to($data['client']->email);
        //     $message->subject('invoice '. $data['from_date']. 'To'.$data['to_date'] );
        //     $message->attach(  asset('storage/pdfInvoices/'.$data['client']->name.'/invoice'. $data['invoice_id'].'.pdf')  );
        // });
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


          Utils::updateBalance($invoice->client_id);
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
      Utils::updateBalance($clientId);

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


        $invoiceNum = InvoiceFactory::getCurrentIncrement();
          $info['source'] = 'massInvoice';

     foreach ($clients as $clientId) {



        $invoice_exists = Invoice::where(['client_id' => $clientId, 'from_date' => $from_date, 'to_date' => $to_date])->first();
        if($invoice_exists !== null ){
        return redirect()->back()->with('error',' אחד או יותר חשבונית כבר קיימת ');
        }

      $client = Client::find($clientId);

        //get all orders between date of created invoice

      $data =  InvoiceFactory::generateInvoice($clientId,$from_date,$to_date,$invoiceNum, $info);
      if(!$data['orders'] == ''){
        $all[$client->name]['invoiceType'] = $request->input('invoiceType');
        $all[$client->name]['orders'] = $data['orders'];
        $all[$client->name]['invoiceInfo'] = $data['invoiceInfo'];
         $all[$client->name]['from_date'] = $data['from_date'];
         $all[$client->name]['to_date'] = $data['to_date'];
         $all[$client->name]['client'] = $client;
        $all[$client->name]['pretax'] =  $data['pretax'];
        $all[$client->name]['tax'] =  $data['tax'];
        $all[$client->name]['posttax'] =  $data['posttax'];
        $all[$client->name]['prevProductsQty'] =  $data['prevProductsQty'];
        $all[$client->name]['prevProductsCosts'] =  $data['prevProductsCosts'];
        $all[$client->name]['discount'] =  0;
        $all[$client->name]['discountType'] =  '';
        $all[$client->name]['feeType'] =  '';
        $all[$client->name]['fee'] =  0;
        $all[$client->name]['notes'] =  '';
        $all[$client->name]['grandTotal'] =  $data['grandTotal'];
        $all[$client->name]['prevdebt'] =  $data['prevdebt'];
        $all[$client->name]['prevcredit'] =  $data['prevcredit'];
       $all[$client->name]['invoiceNum'] =  $data['invoiceNum'];
        $all[$client->name]['originality'] =  $data['originality'];
    $invoiceNum++;
      }

     }



 clearstatcache();
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
           $debt = $clientArray['grandTotal'];



           $exist =   Invoice::where(['client_id' => $clientArray['client']['id'],'from_date' => $from_date , 'to_date' => $to_date])->first();
           // return $clientId;
           if($exist == ''){



           $invoice = new Invoice;
           $invoice->client_id = $clientArray['client']['id'];
           $invoice->from_date = $from_date;
           $invoice->to_date = $to_date;
           $invoice->debt = $debt;
           $invoice->printed = true;


           $invoice->notes = $data['notes'];
           $invoice->documented_debt = $data['prevdebt'];
           $invoice->documented_credit = $data['prevcredit'];
           $invoice->discount = $data['discount'];
           $invoice->fee = $data['fee'];
           $invoice->discount_type = $data['discountType'];
           $invoice->fee_type = $data['feeType'];
           $invoice->paid = 0;

           if ($data['invoiceType'] == 'invoice') {
           $invoice->invoice_num = $data['invoiceNum'];
           }

           $invoice->save();

        }
          $path =  storage_path('app/public/pdfInvoices/'.$clientArray['client']['id']) ;

           if (!File::exists($path))
            {

              File::makeDirectory($path, $mode = 0777, true, true);
            }
            $data['invoiceId'] = $invoice->id;
              $data['isOriginal'] = false;
           $pdf = PDF::loadView('clients.pdfInvoice', compact('data'))
               ->save( storage_path('app/public/pdfInvoices/'.$clientArray['client']['id'].'/invoice'.$invoice->id.'.pdf')  );



          Utils::updateBalance($clientArray['client']['id']);






           $messageType = "success";
           $messageText = "חשבונית נוצרה בהצלחה";
           $sent = $request->input('send');
           if(isset($sent)){
               $data['invoiceId'] = $invoice->id;
               $data['clientId'] = $clientArray['client']['id'];


               $contactInfo = $data['client'];
               $view = 'orders.supplierEmail';
               $attachmentUrl = url('storage/pdfInvoices/'. $data['clientId'].'/invoice'. $data['invoice_id'].'.pdf');
               $subject = 'invoice '. $data['from_date']. 'To'.$data['to_date'];

                dispatch(new sendEmailJob($contactInfo,$view,$subject,$attachmentUrl));
           // Mail::send('orders.supplierEmail', $data, function($message) use ($data){
           //
           //     $message->from('sales@manotboker.com');
           //     $message->to($data['client']['email']);
           //     $message->subject('invoice '. $data['from_date']. 'To'.$data['to_date'] );
           //     $message->attach(  url('storage/pdfInvoices/'.$data['clientId'].'/invoice'.$data['invoiceId'].'.pdf')  );
           // });
           $messageText = "חשבונית נשלחה";
           $invoice->update(['sent' => 1]);
       }
      }


  }



     return redirect()->route('invoices.index')->with($messageType,$messageText);

}
public function originalCopy(Request $request){
  $invoiceId = $request->input('invoice_id');
  $clientId = $request->input('client_id');
  $from_date = Carbon::parse($request->input('from_date'))->format('Y-m-d');
  $to_date = Carbon::parse($request->input('to_date'))->format('Y-m-d');

$info['source'] = 'originalCopy';
$info['invoiceId'] = $invoiceId;
$invoiceNum = 0;
  $data =  InvoiceFactory::generateInvoice($clientId,$from_date,$to_date,$invoiceNum, $info);

$data['invoiceType'] = 'invoice';







$pdf = PDF::loadView('clients.pdfInvoice', compact('data'));

$pdf->stream( 'pdfInvoice.pdf'  );



}



       public function allClientDebts(){
         $clients = Client::orderBy('name', 'asc')->get();
         foreach ($clients as $client) {
          $debt = 0;
           $lastInvoice = Invoice::where('client_id', $client->id)->orderBy('created_at','asc')->first();

           if ($lastInvoice != '') {
             $debt = $lastInvoice->debt;
           }

           $allDebts[$client->name]['lastInvoice'] = $debt ;
           $allDebts[$client->name]['totalDebt'] = $client->debt;
         }
         PDF::loadView('clients.allClientDebtsPdf', compact('allDebts'))
          ->save( storage_path('app/public/admin/allClientDebts.pdf')  );
                 return view('clients.allClientDebts');
//return $allDebts;
       }

public function printInvoiceSummary(Request $request)
{
  if($request->input('from_date') == null || $request->input('to_date') == null ){
    return redirect()->back()->with('error','Fill in Date fields');
  }
  $from_date = Carbon::parse($request->input('from_date'))->format('Y-m-d');
  $to_date = Carbon::parse($request->input('to_date'))->format('Y-m-d');
$invoicesList = [];
   $invoices = Invoice::whereBetween('created_at', [$from_date, $to_date])->whereNotNull('invoice_num')->orderBy('created_at', 'ASC')->get();
if (count($invoices) == 0) {
  return redirect()->back()->with('error','לא נמצאו חשבוניות בתאריכים אלה');
}
//return $invoices;
foreach ($invoices as $invoice) {
  if(Client::find($invoice->client_id)  != ''){
    $clientName = Client::find($invoice->client_id)->name ;
  }else{
    $clientName = "לקוח נמחק";
  }

  $invoicesList[$invoice->invoice_num] = [
    'clientName' => $clientName,
    'total' => $invoice->debt,
    'date' => Carbon::parse($invoice->created_at)->format('d-m-Y')
  ];
}
//return $invoicesList;
$pdf = PDF::loadView('invoices.printInvoicesSummaryPdf', compact('invoicesList'));

$pdf->stream( 'printInvoicesSummary.pdf'  );
}



}
