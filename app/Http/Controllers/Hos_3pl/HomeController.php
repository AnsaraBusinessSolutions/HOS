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
                                ->join('hss_master as hm', 'hm.hospital_code', '=', 'u.hospital_code')
                                ->join('warehouse as w', 'w.wh_id', '=', 'hm.wh_id')
                                ->orderBy('od.order_code','DESC')
                                ->groupBy("od.order_code")
                                ->where('u.user_type',1)
                                ->whereIn('od.status',[1,3])
                                ->select('od.order_code','w.wh_name','od.delivery_date','mm.buom','od.qty','od.status')
                                ->get();
   
        return view('hos_3pl.home', array('all_order'=>$all_order));
    }

    public function requestOrderDetail($order_code){
        $order_detail = DB::table('order_details as od')->select('od.id','mm.nupco_material_generic_code','mm.customer_bp','mm.material_description','mm.buom','od.qty','od.status')
                                        ->join('material_master as mm', 'od.material_master_id', '=', 'mm.id')
                                        ->where('od.order_code', $order_code)
                                        ->get();
        return view('hos_3pl.request_order_details',array('order_detail'=>$order_detail,'order_code'=>$order_code));
    }

    public function orderStatusUpdate(Request $request){
        $order_code = $request->input('order_code');
        $order_status = $request->input('order_status');
        if($order_code != '' && $order_status != ''){
        DB::table('order_details')
        ->where('order_code',$order_code)
        ->update([
            'status' => $order_status,
            'updated_at'=>date("Y-m-d H:i:s") 
            ]);
        }
        return redirect()->route('hos3pl.home');
    }

}
