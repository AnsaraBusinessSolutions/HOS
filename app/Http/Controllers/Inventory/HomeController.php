<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class HomeController extends Controller
{
    public function __construct()
    {
        
        $this->middleware('auth.inventory');
    }

    public function index()
    {   
        
        $user_id = auth()->guard('inventory')->user()->id;
        $all_order = DB::table('order_details as od')
                        ->orderBy('od.status','ASC')
                        ->groupBy("od.order_id")
                        ->whereIn('od.status',[3])
                        ->select('od.order_id','od.supplying_plant','od.hospital_name','od.delivery_date','od.uom','od.qty_ordered','od.status','od.created_date')
                        ->selectRaw('sum(od.qty_ordered) as total_qty')
                        ->selectRaw('count(od.order_id) as total_item')
                        ->get();
   
        return view('inventory.home', array('all_order'=>$all_order));
    }
}
