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
        $all_order = DB::table('pgi_details as pd')
                        ->groupBy("pd.order_id")
                        ->select('pd.order_id','pd.supplying_plant','pd.hospital_name','pd.delivery_date','pd.uom','pd.qty_ordered','pd.created_at')
                        ->selectRaw('sum(pd.qty_ordered) as total_qty')
                        ->selectRaw('sum(pd.batch_qty) as dispatch_qty')
                        ->selectRaw('count(pd.order_id) as dispatch_item')
                        ->selectRaw(DB::raw('group_concat(distinct pd.pgi_status) as status'))
                        ->orderBy('status','ASC')
                        ->orderBy('pd.order_id','DESC')
                        ->get();
                       
        return view('inventory.home', array('all_order'=>$all_order));
    }

    public function index_old()
    {   
        $user_id = auth()->guard('inventory')->user()->id;
        $all_order = DB::table('order_details as od')
                        ->groupBy("od.order_id")
                        ->select('od.order_id','od.supplying_plant','od.hospital_name','od.delivery_date','od.uom','od.qty_ordered','od.created_date')
                        ->selectRaw('sum(od.qty_ordered) as total_qty')
                        ->selectRaw('count(od.order_id) as total_item')
                        ->selectRaw(DB::raw('group_concat(distinct od.status) as status'))
                        ->having('status','2,3')
                        ->orHaving('status','2,3,4')
                        ->orHaving('status','3,4')
                        ->orHaving('status',3)
                        ->orHaving('status',4)
                        ->orderBy('status','ASC')
                        ->orderBy('od.order_id','DESC')
                        ->get();
                       
        return view('inventory.home', array('all_order'=>$all_order));
    }

    public function orderDetail($order_id){
        $order_detail = DB::table('pgi_details as pd')
                                        ->join('hss_master as hs','pd.hss_master_no','=','hs.hss_master_no')
                                        ->leftjoin('grn_details as gd','pd.order_main_id','=','gd.order_main_id')
                                        ->select('gd.received_qty','pd.hss_master_no','pd.hospital_name','hs.delivery_wh_name','hs.address','pd.id','pd.pgi_id','pd.order_id','pd.category','pd.nupco_generic_code','pd.nupco_trade_code','pd.customer_trade_code','pd.material_desc','pd.uom','pd.qty_ordered','pd.delivery_date','pd.created_at','pd.batch_qty','pd.batch_no')
                                        ->where('pd.order_id', $order_id)
                                        ->get();

        // dd($order_detail);exit;

        $status = DB::table('order_details as od')->select(DB::raw('group_concat(distinct od.status) as status'))->where('od.order_id', $order_id)->first();

        return view('inventory.order_details',array('order_detail'=>$order_detail,'order_id'=>$order_id,'status_data'=>$status));
    }

    public function createGrn(Request $request){
        $pgi_main_id_arr = $request->input('pgi_main_id');
        $received_qty_arr = $request->input('received_qty');
        
        if(count($received_qty_arr) > 0 && count($pgi_main_id_arr) > 0){
            $grn_no = '600-000-001';
            $last_grn_id = DB::table('grn_details')->select('grn_id')->orderBy('grn_id', 'DESC')->first();
            if(empty($last_grn_id)){
                $lasts_grn_id = '600-000-000';
            }else{
                $lasts_grn_id=$last_grn_id->grn_id;
            }
            if($lasts_grn_id!==''){
                $grn_no=str_replace('-', '',$lasts_grn_id);
                $grn_no=$grn_no+1;
                $grn_no=str_pad($grn_no,9,'0',STR_PAD_LEFT);
                $grn_no = implode('-',str_split($grn_no,3));
            }

            foreach($pgi_main_id_arr as $key=>$val){
                $pgi_data =  DB::table('pgi_details')->where('id',$val)->first();
                if(!empty($pgi_data)){
                    $grn_data_arr[] = array('grn_id'=>$grn_no,
                    'pgi_id'=>$pgi_data->pgi_id,
                    'pgi_main_id'=>$pgi_data->id,
                    'received_qty'=>$received_qty_arr[$key],
                    'batch_qty'=>$pgi_data->batch_qty,
                    'batch_no'=>$pgi_data->batch_no,
                    'manufacture_date'=>$pgi_data->manufacture_date,
                    'expiry_date'=>$pgi_data->expiry_date,
                    'order_id'=>$pgi_data->order_id,
                    'order_main_id'=>$pgi_data->order_main_id,
                    'category'=>$pgi_data->category,
                    'nupco_generic_code'=>$pgi_data->nupco_generic_code,
                    'nupco_trade_code'=>$pgi_data->nupco_trade_code,
                    'customer_trade_code'=>$pgi_data->customer_trade_code,
                    'material_desc'=>$pgi_data->material_desc,
                    'qty_ordered'=>$pgi_data->qty_ordered,
                    'uom'=>$pgi_data->uom,
                    'delivery_date'=>$pgi_data->delivery_date,
                    'supplying_plant'=>$pgi_data->supplying_plant,
                    'hss_master_no'=>$pgi_data->hss_master_no,
                    'hospital_name'=>$pgi_data->hospital_name,
                    'vehicle_no'=>$pgi_data->vehicle_no,
                    'created_at'=>date('Y-m-d H:i:s'));

                    $order_main_id_arr[] = $pgi_data->order_main_id;


                }
            }
          
            if(!empty($grn_data_arr)){
                $result = DB::table('grn_details')->insert($grn_data_arr);

                if(!empty($order_main_id_arr)){
                    DB::table('order_details')
                        ->whereIn('id',$order_main_id_arr)
                        ->update([
                            'status' => 4,
                            'last_updated_date'=>date("Y-m-d H:i:s"),
                            'last_updated_user'=>auth()->guard('inventory')->user()->name
                            ]);
                }
            }
            return redirect()->route('inventory.home');
        }
    }
}
