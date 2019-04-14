<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Price;
use App\Client;
use App\Product;
use App\Credit;
use App\CreditItem;
use Utils;
use App\Invoice;
use DB;
use File;
use PDF;
use Carbon\Carbon;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($client_id)
    {
        $client = Client::find($client_id);


      $data = array(
          'client' => $client
      );
      return view('credit.createCredit')->with('data', $data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      $client = Client::find($request['client_id']);
      $credit_items_array =  $request['credit_items'];


////get sum of totals of  all rows
      $totals = [];
      foreach ($credit_items_array as $key => $array) {
        array_push($totals, $array['total']);
      }
      $total_credit =   array_sum($totals);

      if ($total_credit <= 0 ) {
          // return view('credit.createCredit')->with('error', 'no entry');
return redirect()->back()->with('error',' no entry ');
      }
      $credit_num = '';
      if(Credit::orderBy('credit_num','desc')->first() !== null){
        $credit_num =   Credit::orderBy('credit_num','desc')->first()->credit_num + 1;

      }
      if ($credit_num !== '') {
        $credit_num = $credit_num;
      }else {
        $credit_num = 1;
      }



          $credit = new Credit;
          $credit->client_id = $client->id;
          $credit->amount = $total_credit;
          $credit->credit_num = $credit_num;
          $credit->save();

        foreach ($credit_items_array as $row ) {

          $credit_item = new CreditItem;
          $credit_item->credit_id = $credit->id;
          $credit_item->product_amount = $row['amount'];
          $credit_item->description = $row['description'];
          $credit_item->unit_price = $row['unit_price'];
          $credit_item->total_credit = $row['total'];
          $credit_item->save();


        }


        $currentCredit = Credit::find($credit->id);
       $creditItems =  $currentCredit->creditItems()->get();


           Utils::updateBalance($client->id);
      $date =  Carbon::parse($credit->created_at)->format('d-m-Y') ;
           $data =  array(
             'currentCredit' => $currentCredit ,
             'creditItems' => $creditItems,
             'creditType' => $request['creditType'],
             'originality' => 'original',
             'client' => $client,
             'total_credit' => $total_credit,
             'date' => $date

            );

           $path =  storage_path('app/public/creditPdfs/'.$client->id) ;

                  if (!File::exists($path))
                   {
                     File::makeDirectory($path, $mode = 0777, true, true);
                   }
           $pdf = PDF::loadView('credit.creditPdf', compact('data'))
           ->save( storage_path('app/public/creditPdfs/'.$client->id.'/credit'.$credit->id.'.pdf')  );

        return redirect()->route('credit.show',$credit->id)->with('success','נשמרה בהצלחה');

    }





    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $credit = Credit::find($id);
      $client = Client::find($credit->client_id);
     
      $data = array(
        'client' => $client
      );

            return view('credit.creditShow')->with(['client' => $client,
                                                        'credit' => $credit,
                                                       'data' => $data

                                                     ]);
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
