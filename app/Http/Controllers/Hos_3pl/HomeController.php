<?php

namespace App\Http\Controllers\Hos_3pl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.hos3pl');
    }

    public function index()
    {
        $user_id = auth()->guard('hos3pl')->user()->id;
       // $user_id = Auth::user()->id;
        $all_order = DB::table('order_details as od')
                                ->orderBy('od.status','ASC')
                                ->groupBy("od.order_id")
                                ->whereIn('od.status',[2,3])
                                ->select('od.order_id','od.supplying_plant','od.delivery_date','od.uom','od.qty_ordered','od.status','od.created_date')
                                ->selectRaw('sum(od.qty_ordered) as total_qty')
                                ->selectRaw('count(od.order_id) as total_item')
                                ->get();
   
        return view('hos_3pl.home', array('all_order'=>$all_order));
    }

    public function requestOrderDetail($order_id){
        $order_detail = DB::table('order_details as od')
                        ->select('od.id','od.nupco_generic_code','od.nupco_trade_code','od.customer_trade_code','od.category','od.material_desc','od.uom','od.qty_ordered','od.delivery_date','od.status',DB::raw("(SELECT count(bl.id) FROM batch_list as bl WHERE bl.order_id = od.id) as batch_count"))
                        ->where('od.order_id', $order_id)
                        ->get();
        
        $total_qty = 0;
        foreach ($order_detail as $key=>$value) {
            $total_qty += $value->qty_ordered;
        }
        return view('hos_3pl.request_order_details',array('order_detail'=>$order_detail,'order_id'=>$order_id,'total_qty'=>$total_qty));
    }

    public function orderStatusUpdate(Request $request){
        $order_id = $request->input('order_id');
        if($order_id != ''){
        DB::table('order_details')
        ->where('order_id',$order_id)
        ->update([
            'status' => 3,
            'vehicle_no' => $request->input('vehical_number'),
            'last_updated_date'=>date("Y-m-d H:i:s"),
            'last_updated_user'=>auth()->guard('hos3pl')->user()->name
            ]);
        }
        return redirect()->route('hos3pl.home');
    }

    public function orderBatchInsert(Request $request){
        $batch_qty_array = $request->input('batch_qty');
        $batch_no_array = $request->input('batch_no');
        $manufacture_date_array = $request->input('manufacture_date');
        $expiry_date_array = $request->input('expiry_date');
        $order_id = $request->input('order_id');
        foreach($batch_qty_array as $key=>$val) {
            if(!empty($val) && $order_id != ''){ 
                $batch_data[] = array('order_id' => $order_id,
                                    'batch_qty' => $val,
                                    'batch_no' => $batch_no_array[$key],
                                    'manufacture_date'=>$manufacture_date_array[$key],
                                    'expiry_date'=>$expiry_date_array[$key],
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s') );
            }
        }
        $result = 0;
        if(!empty($batch_data)){
            $result = DB::table('batch_list')->insert($batch_data);
        }

        if($result){
            $status = 1;
            $request->session()->flash("message","<div class='col-12 text-center alert alert-success' role='alert'>Batch Added Successfully<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden = 'true' >&times; </span></button></div>");
        }else{
            $status = 0;
            $request->session()->flash("message","<div class='col-12 text-center alert alert-danger' role='alert'>Something went wrong.Please try again or contact to admin<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden = 'true' >&times; </span></button></div>");
        }

        return back();

    }
    

}
