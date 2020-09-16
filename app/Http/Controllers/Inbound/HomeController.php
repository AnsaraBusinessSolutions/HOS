<?php

namespace App\Http\Controllers\Inbound;

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
}
