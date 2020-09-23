<?php

namespace App\Http\Controllers\Custodian;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.custodian');
        
    }

    public function index()
    {
        $user_id = auth()->guard('custodian')->user()->id;
       // $user_id = Auth::user()->id;
        $all_order = DB::table('order_details as od')
                                ->orderBy('od.status','ASC')
                                ->groupBy("od.order_id")
                                ->whereIn('od.status',[0,1,2])
                                ->select('od.order_id','od.supplying_plant','od.hospital_name','od.delivery_date','od.uom','od.qty_ordered','od.status','od.created_date')
                                ->selectRaw('sum(od.qty_ordered) as total_qty')
                                ->selectRaw('count(od.order_id) as total_item')
                                ->get();
   
        return view('custodian.home', array('all_order'=>$all_order));
    }

    public function requestOrderDetail($order_id){
        $order_detail = DB::table('order_details as od')
                        ->select('od.id','od.nupco_generic_code','od.nupco_trade_code','od.customer_trade_code','od.category','od.material_desc','od.uom','od.qty_ordered','od.delivery_date','od.status',DB::raw("(SELECT count(bl.id) FROM batch_list as bl WHERE bl.order_id = od.id) as batch_count"))
                        ->where('od.order_id', $order_id)
                        ->get();
        return view('custodian.request_order_details',array('order_detail'=>$order_detail,'order_id'=>$order_id));
    }

    public function orderUpdate(Request $request){
        $order_id_arr = $request->input('order_id');
        $qty_arr = $request->input('qty_ordered');
      
        foreach($order_id_arr as $key=>$val){
            DB::table('order_details')
                ->where('id',$val)
                ->update([
                    'qty_ordered' => $qty_arr[$key],
                    'last_updated_date'=>date("Y-m-d H:i:s"),
                    'last_updated_user'=>auth()->guard('custodian')->user()->name,
            ]);
        }
        return back()->with("message","<div class='col-12 text-center alert alert-success' role='alert'>Request Updated Successfully<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden = 'true' >&times; </span></button></div>");
    }

    public function orderRejected(Request $request){
         $order_id = $request->input('order_id');
         $rejection_date = date("Y-m-d");
         if($order_id != ''){
            DB::table('order_details')
                ->where('order_id',$order_id)
                ->update([
                    'rejection_reason' => $request->input('rejection_reason'),
                    'rejection_date'=>$rejection_date,
                    'status'=>1
            ]);
        }
        return redirect('custodian/home');
    }

    public function orderApprove(Request $request){
        $order_id = $request->input('order_id');
        if($order_id != ''){
        $approve_date = date("Y-m-d");
           DB::table('order_details')
               ->where('order_id',$order_id)
               ->update([
                   'approve_comment' => $request->input('approve_comment'),
                   'approve_date' => $approve_date,
                   'status'=>2
           ]);
       }
       return redirect('custodian/home');
   }


   public function batchData(Request $request){
        $order_id = $request->input('order_id');
        
        $batch_data = array();
        if($order_id != ''){
            $batch_data = DB::table('batch_list')
                                    ->select('batch_qty','batch_no','manufacture_date','expiry_date')
                                    ->where('order_id',$order_id)->get();
        }
        return $batch_data;
    }

}
