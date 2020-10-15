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
        $hss_master_no = auth()->guard('inventory')->user()->hss_master_no;

        $all_order = DB::table('pgi_details as pd')
                        ->groupBy("pd.order_id")
                        ->select('pd.order_id','pd.supplying_plant','pd.hospital_name','pd.delivery_date','pd.uom','pd.qty_ordered','pd.created_at')
                        ->selectRaw('sum(pd.qty_ordered) as total_qty')
                        ->selectRaw('sum(pd.batch_qty) as dispatch_qty')
                        ->selectRaw('count(pd.order_id) as dispatch_item')
                        ->selectRaw(DB::raw('group_concat(distinct pd.pgi_status) as status'))
                        ->where('hss_master_no',$hss_master_no)
                        ->orderBy('status','ASC')
                        ->orderBy('pd.order_id','DESC')
                        ->get();
                       
        return view('inventory.home', array('all_order'=>$all_order));
    }

    public function orderDetail($order_id){
        $order_detail = DB::table('pgi_details as pd')
                                        ->join('hss_master as hs','pd.hss_master_no','=','hs.hss_master_no')
                                        ->leftjoin('grn_details as gd','pd.order_main_id','=','gd.order_main_id')
                                        ->select('pd.pgi_status','gd.received_qty','pd.hss_master_no','pd.hospital_name','hs.delivery_wh_name','hs.address','pd.id','pd.pgi_id','pd.order_id','pd.category','pd.nupco_generic_code','pd.nupco_trade_code','pd.customer_trade_code','pd.material_desc','pd.uom','pd.qty_ordered','pd.delivery_date','pd.created_at','pd.batch_qty','pd.batch_no','pd.manufacture_date','pd.expiry_date')
                                        ->where('pd.order_id', $order_id)
                                        ->get();
       
        $status = DB::table('pgi_details as od')->select(DB::raw('group_concat(distinct od.pgi_status) as status'))->where('od.order_id', $order_id)->first();

        return view('inventory.order_details',array('order_detail'=>$order_detail,'order_id'=>$order_id,'status_data'=>$status));
    }

    public function createGrn(Request $request){
        $pgi_main_id_arr = $request->input('pgi_main_id');
        $received_qty_arr = $request->input('received_qty');
        $order_id = $request->input('order_id');
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
                    'supplying_plant_code'=>$pgi_data->supplying_plant_code,
                    'supplying_plant'=>$pgi_data->supplying_plant,
                    'sloc_id'=>$pgi_data->sloc_id,
                    'hss_master_no'=>$pgi_data->hss_master_no,
                    'hospital_name'=>$pgi_data->hospital_name,
                    'vehicle_no'=>$pgi_data->vehicle_no,
                    'created_at'=>date('Y-m-d H:i:s'));

                    $order_main_id_arr[] = $pgi_data->order_main_id;
                    $pgi_arr[] = array('pgi_main_id'=>$pgi_data->id,'order_main_id'=>$pgi_data->order_main_id);


                    $check_stock_available = DB::table('stock as s')
                                            ->where('s.nupco_generic_code',$pgi_data->nupco_generic_code)
                                            ->where('s.vendor_batch',$pgi_data->batch_no)
                                            ->where('s.mfg_date',$pgi_data->manufacture_date)
                                            ->where('s.expiry_date',$pgi_data->expiry_date) 
                                            ->where('s.plant',$pgi_data->supplying_plant_code) 
                                            ->where('s.storage_location',$pgi_data->sloc_id) 
                                            ->select('s.id')
                                            ->first();
                    
                    if(!empty($check_stock_available)){
                        DB::table('stock')
                        ->where('id',$check_stock_available->id)
                        ->increment('unrestricted_stock_qty',$received_qty_arr[$key]);

                        DB::table('stock')
                        ->where('id',$check_stock_available->id)
                        ->update([
                            'last_updated_at'=>date("Y-m-d H:i:s"),
                            ]);

                    }else{
                        $stock_data = array('customer'=>$pgi_data->hss_master_no,
                        'nupco_generic_code'=>$pgi_data->nupco_generic_code,
                        'nupco_trade_code'=>$pgi_data->nupco_trade_code,
                        'customer_trade_code'=>$pgi_data->customer_trade_code,
                        'nupco_desc'=>$pgi_data->material_desc,
                        'plant'=>$pgi_data->supplying_plant_code,
                        'storage_location'=>$pgi_data->sloc_id,
                        'unrestricted_stock_qty'=>$received_qty_arr[$key],
                        'vendor_batch'=>$pgi_data->batch_no,
                        'uom'=>$pgi_data->uom,
                        'batch'=>'',
                        'mfg_date'=>$pgi_data->manufacture_date,
                        'expiry_date'=>$pgi_data->expiry_date,
                        'created_at'=>date('Y-m-d H:i:s'));

                        DB::table('stock')->insert($stock_data);
                    }

                }

            }
          
            if(!empty($grn_data_arr)){
                $result = DB::table('grn_details')->insert($grn_data_arr);

                if(count($pgi_arr) > 0){
                    foreach($pgi_arr as $key=>$val){
                        $received_qty = DB::table('grn_details as gd')
                        ->where('gd.pgi_main_id',$val['pgi_main_id'])
                        ->select('gd.batch_qty')
                        ->selectRaw('sum(gd.received_qty) as total_received_qty')
                        ->first();

                        if(!empty($received_qty)){
                            $change_order_status = 4;
                            if($received_qty->total_received_qty < $received_qty->batch_qty){
                                $change_order_status = 8;
                            }elseif($received_qty->total_received_qty == $received_qty->batch_qty){
                                $change_order_status = 6;
                            }
                            
                            $order_main_id_val = $val['order_main_id'];

                            DB::table('order_details')
                            ->where('id',$order_main_id_val)
                            ->update([
                                'status' => $change_order_status,
                                'last_updated_date'=>date("Y-m-d H:i:s"),
                                'last_updated_user'=>auth()->guard('inventory')->user()->name
                                ]);

                            DB::table('pgi_details')
                            ->where('id',$val['pgi_main_id'])
                            ->update([
                                'pgi_status' => $change_order_status,
                                ]);
                        }
                    }
                }

                    $total_order_qty_sum = DB::table('order_details as od')
                    ->where('od.order_id',$order_id)
                    ->where('od.is_deleted', 0)
                    ->selectRaw('sum(od.qty_ordered) as total_order')
                    ->first();

                    $total_received_qty_sum = DB::table('grn_details as gd')
                    ->where('gd.order_id',$order_id)
                    ->selectRaw('sum(gd.received_qty) as total_received')
                    ->first();
                
                    if(!empty($total_order_qty_sum) && !empty($total_received_qty_sum)){
                        if($total_order_qty_sum->total_order == $total_received_qty_sum->total_received){
                            DB::table('order_details')
                            ->where('order_id',$order_id)
                            ->update([
                                'status' => 4,
                                'last_updated_date'=>date("Y-m-d H:i:s"),
                                'last_updated_user'=>auth()->guard('inventory')->user()->name
                                ]);

                            DB::table('pgi_details')
                            ->where('order_id',$order_id)
                            ->update([
                                'pgi_status' => 4,
                                ]);
                        }
                    }
            }
            if($request->has('redirect_page_name')){
                return redirect()->route('inventory.open.order');
            }
            return redirect()->route('inventory.home');
        }
    }

    public function openOrder()
    {   
        $user_id = auth()->guard('inventory')->user()->id;
        $hss_master_no = auth()->guard('inventory')->user()->hss_master_no;

        $all_order = DB::table('pgi_details as pd')
                        ->groupBy("pd.order_id")
                        ->select('pd.order_id','pd.supplying_plant','pd.hospital_name','pd.delivery_date','pd.uom','pd.qty_ordered','pd.created_at')
                        ->selectRaw('sum(pd.qty_ordered) as total_qty')
                        ->selectRaw('sum(pd.batch_qty) as dispatch_qty')
                        ->selectRaw('count(pd.order_id) as dispatch_item')
                        ->selectRaw(DB::raw('group_concat(distinct pd.pgi_status) as status'))
                        ->orderBy('status','ASC')
                        ->orderBy('pd.order_id','DESC')
                        ->where('pd.pgi_status','!=',4)
                        ->where('pd.pgi_status','!=',6)
                        ->where('hss_master_no',$hss_master_no)
                        ->get();
                       
        return view('inventory.open_order', array('all_order'=>$all_order));
    }

    public function openOrderDetail($order_id){
        $order_detail = DB::table('pgi_details as pd')
                                        ->join('hss_master as hs','pd.hss_master_no','=','hs.hss_master_no')
                                        ->leftjoin('grn_details as gd','pd.order_main_id','=','gd.order_main_id')
                                        ->select('pd.pgi_status','gd.received_qty','pd.hss_master_no','pd.hospital_name','hs.delivery_wh_name','hs.address','pd.id','pd.pgi_id','pd.order_id','pd.category','pd.nupco_generic_code','pd.nupco_trade_code','pd.customer_trade_code','pd.material_desc','pd.uom','pd.qty_ordered','pd.delivery_date','pd.created_at','pd.batch_qty','pd.batch_no','pd.manufacture_date','pd.expiry_date')
                                        ->where('pd.order_id', $order_id)
                                        ->whereIn('pd.pgi_status', [3,5,7])
                                        ->get();
       
        $status = DB::table('pgi_details as od')->select(DB::raw('group_concat(distinct od.pgi_status) as status'))->where('od.order_id', $order_id)->first();

        return view('inventory.open_order_details',array('order_detail'=>$order_detail,'order_id'=>$order_id,'status_data'=>$status));
    }

    public function displayOrder()
    {
        $user_id = auth()->guard('inventory')->user()->id;
        $hss_master_no = auth()->guard('inventory')->user()->hss_master_no;
        
        $all_order = DB::table('grn_details as gd')
                                ->groupBy("gd.order_id")
                                ->select('gd.order_id','gd.supplying_plant','gd.hospital_name','gd.delivery_date','gd.uom','gd.qty_ordered','gd.created_at')
                                ->selectRaw('sum(gd.received_qty) as total_batch_qty')
                                ->selectRaw("count(DISTINCT(gd.id)) as total_item")
                                ->where('hss_master_no',$hss_master_no)
                                ->orderBy('gd.order_id','DESC')
                                ->get();
   
        return view('inventory.display_order', array('all_order'=>$all_order));
    }

    public function displayOrderDetail($order_id)
    {
        $order_detail = DB::table('grn_details as gd')
        ->join('hss_master as hs','gd.hss_master_no','=','hs.hss_master_no')
        ->join('order_details as od','gd.order_main_id','=','od.id')
        ->select('gd.batch_qty','od.created_date','hs.delivery_wh_name','hs.address','gd.hss_master_no','gd.hospital_name','gd.id','gd.pgi_id','gd.order_id','gd.order_main_id','gd.nupco_generic_code','gd.nupco_trade_code','gd.customer_trade_code','gd.category','gd.material_desc','gd.uom','gd.qty_ordered','gd.delivery_date','gd.created_at','gd.batch_qty','gd.batch_no','gd.manufacture_date','gd.expiry_date','gd.received_qty','gd.grn_id')
        ->selectraw('sum(gd.batch_qty) as batch_qty')
        ->where('gd.order_id', $order_id)
        ->groupBy(DB::raw("gd.order_main_id,gd.pgi_id"))
        ->get();

        $status = DB::table('order_details as od')->select(DB::raw('group_concat(distinct od.status) as status'))->where('od.order_id', $order_id)->first();

        $total_qty = 0;
        foreach ($order_detail as $key=>$value) {
        $total_qty += $value->qty_ordered;
        }
        return view('inventory.display_order_details',array('order_detail'=>$order_detail,'order_id'=>$order_id,'total_qty'=>$total_qty,'status_data'=>$status));
    }
}
