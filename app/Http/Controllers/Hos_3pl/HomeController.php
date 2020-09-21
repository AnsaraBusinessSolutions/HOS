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
                                ->join('material_master as mm', 'od.material_master_id', '=', 'mm.id')
                                ->join('users as u', 'u.id', '=', 'od.user_id')
                                ->join('hss_master as hs', 'hs.id', '=', 'u.hss_master_id')
                                ->orderBy('od.order_code','DESC')
                                ->groupBy("od.order_code")
                                ->whereIn('od.status',[1,3])
                                ->select('od.id','od.order_code','hs.delivery_wh_name','od.delivery_date','mm.uom','od.qty','od.status','od.created_at')
                                ->selectRaw('sum(od.qty) as total_qty')
                                ->get();
   
        return view('hos_3pl.home', array('all_order'=>$all_order));
    }

    public function requestOrderDetail($order_code){
        $order_detail = DB::table('order_details as od')
                            ->select('od.id','mm.nupco_generic_code','mm.nupco_trade_code','mm.customer_code','mm.nupco_desc','mm.uom','od.qty','od.status','od.delivery_date')
                            ->join('material_master as mm', 'od.material_master_id', '=', 'mm.id')
                            ->where('od.order_code', $order_code)
                            ->get();
        
        $total_qty = 0;
        foreach ($order_detail as $key=>$value) {
            $total_qty += $value->qty;
        }
        return view('hos_3pl.request_order_details',array('order_detail'=>$order_detail,'order_code'=>$order_code,'total_qty'=>$total_qty));
    }

    public function orderStatusUpdate(Request $request){
        $order_code = $request->input('order_code');
        if($order_code != ''){
        DB::table('order_details')
        ->where('order_code',$order_code)
        ->update([
            'status' => 3,
            'vehicle_no' => $request->input('vehical_number'),
            'updated_at'=>date("Y-m-d H:i:s") 
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
