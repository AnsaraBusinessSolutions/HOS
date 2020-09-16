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
        $this->middleware('auth');
    }

    public function index()
    {
        $user_id = Auth::user()->id;
        $all_order = DB::table('order_details as od')
                                ->join('material_master as mm', 'od.material_master_id', '=', 'mm.id')
                                ->join('users as u', 'u.id', '=', 'od.user_id')
                                ->join('hss_master as hm', 'hm.hospital_code', '=', 'u.hospital_code')
                                ->join('warehouse as w', 'w.wh_id', '=', 'hm.wh_id')
                                ->orderBy('od.order_code','DESC')
                                ->groupBy("od.order_code")
                                ->where('u.user_type',1)
                                ->select('od.order_code','w.wh_name','od.delivery_date','mm.buom','od.qty','od.status')
                                ->get();
   
        return view('inbound.home', array('all_order'=>$all_order));
    }

    public function requestOrderDetail($order_code){
        $order_detail = DB::table('order_details as od')->select('od.id','mm.nupco_material_generic_code','mm.customer_bp','mm.material_description','mm.buom','od.qty','od.status')
                                        ->join('material_master as mm', 'od.material_master_id', '=', 'mm.id')
                                        ->where('od.order_code', $order_code)
                                        ->get();
        return view('inbound.request_order_details',array('order_detail'=>$order_detail,'order_code'=>$order_code));
    }

    public function orderUpdate(Request $request){
        $order_id_arr = $request->input('order_id');
        $qty_arr = $request->input('qty');
      

        foreach($order_id_arr as $key=>$val){
            DB::table('order_details')
                ->where('id',$val)
                ->update([
                    'qty' => $qty_arr[$key],
                    'updated_at'=>date("Y-m-d H:i:s")
            ]);
        }

        return back()->with("message","<div class='col-12 text-center alert alert-success' role='alert'>Request Updated Successfully<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden = 'true' >&times; </span></button></div>");
    }

    public function orderRejected(Request $request){
         $order_code = $request->input('order_code');
         $rejection_date = date("Y-m-d", strtotime($request->input('rejection_date')));
         if($order_code != ''){
            DB::table('order_details')
                ->where('order_code',$order_code)
                ->update([
                    'rejection_reason' => $request->input('rejection_reason'),
                    'rejection_date'=>$rejection_date,
                    'status'=>2
            ]);
        }
        return redirect('inbound/home');
    }

    public function orderApprove(Request $request){
        $order_code = $request->input('order_code');
        if($order_code != ''){
           DB::table('order_details')
               ->where('order_code',$order_code)
               ->update([
                   'approve_comment' => $request->input('approve_comment'),
                   'status'=>1
           ]);
       }
       return redirect('inbound/home');
   }

    


}
