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
use Illuminate\Support\Facades\Mail;
use App\Jobs\sendEmailJob;
use App\Mail\sendEmail;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplier =  Supplier::find(Input::get('supplierId'));
        $reports =   $supplier->missingReports()->get();

         $allReports =[];
         foreach ($reports as $report) {
          // $date =   Carbon::parse($missingProduct->date)->format('m-Y');
             $allReports[  Carbon::parse($report->from_date)->format('m-Y')][] = $report;

         }
        // return $allReports;
         return view('missingProducts.allMissingReports')->with(['supplier'=>$supplier,
                                                                 'allReports'=> $allReports]);

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
    public function store(Request $request )
    {

         $supplier = Supplier::find($request->input('supplier_id'));




        $missingReport = new MissingReport;
        $missingReport->supplier_id = $supplier->id;
        $missingReport->from_date = $request->input('from_date');
        $missingReport->to_date = $request->input('to_date');
        $missingReport->save();

        $messageText = 'הדוח נשמר';
        $messageType = 'success';
        if($request->input('send') != ''){

            $data = array(
                'supplier' => $supplier,
                'from_date' => $request->input('from_date'),
                'to_date' => $request->input('to_date')

            );
              $contactInfo = $data['supplier'];
              $view = 'emails.missingReport';
              $attachmentUrl = url('storage/missingReportsPdf/mReport'.$data['from_date']. '~~' . $data['to_date'] .'.pdf');
              $subject = 'Missing Products '. $data['from_date']. 'To'.$data['to_date'];

               dispatch(new sendEmailJob($contactInfo,$view,$subject,$attachmentUrl));
            // Mail::send('emails.missingReport', $data, function($message) use ($data){
            //
            //     $message->from('sales@manotboker.com');
            //     $message->to($data['supplier']['email']);
            //     $message->subject('Missing Products '. $data['from_date']. 'To'.$data['to_date'] );
            //     $message->attach(  url('storage/missingReportsPdf/mReport'.$data['from_date']. '~~' . $data['to_date'] .'.pdf')  );
            // });
            $messageText = 'דווח נשלח';
            $messageType = 'success';
        }
        return redirect()->route('suppliers.show',$supplier->id)->with($messageType,$messageText);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

       $report = MissingReport::find($id);
       $supplier = $report->supplier()->first();
        $from_date = Carbon::parse($report->from_date)->format('Y-m-d');
        $to_date =  Carbon::parse($report->to_date)->format('Y-m-d');

        return view('missingProducts.showReport')->with(['report' =>$report,'supplier' => $supplier ,'from_date' => $from_date, 'to_date' => $to_date]);

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
        $supplierId =  MissingReport::find($id)->supplier_id;
        MissingReport::find($id)->delete();
        return redirect()->route('reports.index',['supplierId' => $supplierId ])->with('success','דוח נמחקה');
    }
}
