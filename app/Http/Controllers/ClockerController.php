<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clocker;
use Session;
use Carbon\Carbon;
class ClockerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


      return view('pages.clockerIndex');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

          $wage = $request->input('wage');
         $from_date =   Carbon::parse($request->input('from_date'))->format('Y-m-d');
         $to_date =   Carbon::parse($request->input('to_date'))->format('Y-m-d');
         $employee = $request->input('employee');

         $results =  Clocker::whereBetween('date',[$from_date,$to_date])->where('employee',$employee)->orderBy('date', 'asc')->get();
         if($results->count() == 0 ){
        return   redirect()->back()->with('error','No record Found');
         }
      foreach ($results as $result) {
          $array[ Carbon::parse($result->date)->format('d-m-Y')][$result->id]   =   $result->start->diffInseconds($result->end);
          $totalTimes[$result->id] = $result->start->diffInseconds($result->end);
      }

    $totalTime =   array_sum($totalTimes);
return view('clocker.clockerCreate')->with('array', $array)->with(['wage'=>$wage, 'totalTime' => $totalTime]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {



      date_default_timezone_set("Asia/Jerusalem");
        $date = Carbon::now()->format("Y-m-d");

      //
      if (Session::get('started') == true){
        Clocker::where(['date' => $date, 'end'=> null , 'employee' => $request->input('employee')])->update(['end' => Carbon::now()]);
        Session::forget('started');
        Session::forget('employee');
      }else{
        $entry = new Clocker;
        $entry->date = $date;
        $entry->start = Carbon::now();
        $entry->employee = $request->input('employee');
        $entry->save();
        Session::put('started', Carbon::now());
        Session::put('employee', $request->input('employee'));

      }



    return redirect()->back();


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

      $date =   Carbon::parse($request->input('date'))->format('Y-m-d');
      $employee = $request->input('employee');
      $results =  Clocker::where('date',$date)->where('employee',$employee)->get();

      if($results->count() == 0 ){
     return   redirect()->back()->with('error','No record Found');
      }
   foreach ($results as $result) {
       $array[$result->id]   =   gmdate("H:i:s",$result->start->diffInseconds($result->end));

   }

        return view('clocker.clockerShow')->with(['array'=> $array, 'date'=>Carbon::parse($date)->format('d-m-Y')]);
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

      $date = Clocker::find($id)->date;
      $employee= Clocker::find($id)->employee;
      $data = array(
        'date' => $date,
        'employee' => $employee
       );

      Clocker::find($id)->delete();
      $results = Clocker::where(['date' => $date, 'employee' => $employee])->get();

      if($results->count() > 0 ){
        return redirect()->action('ClockerController@show', $data);
      }else{
        return redirect()->route('clocker.index')->with('success', 'נמחקה');
      }

    }
}
