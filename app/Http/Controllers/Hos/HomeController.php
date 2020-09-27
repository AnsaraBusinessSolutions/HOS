<?php

namespace App\Http\Controllers\Hos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Hos\MaterialMaster;
use App\Model\Hos\OrderDetail;
use Auth;
use DB;
use Session;
ini_set('memory_limit', '-1');

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
                                ->where('od.user_id','=',$user_id)
                                ->orderBy('od.status','ASC')
                                ->orderBy('od.order_id','DESC')
                                ->groupBy("od.order_id")
                                ->select('od.order_id','od.supplying_plant','od.delivery_date','od.uom','od.qty_ordered','od.status','od.created_date')
                                ->selectRaw('sum(od.qty_ordered) as total_qty')
                                ->selectRaw('count(od.order_id) as total_item')
                                ->get();
   
        return view('hos.home', array('all_order'=>$all_order));
    }

    public function storeOrder()
    {
        $hss_master_id = Auth::user()->hss_master_id;
        $delivery_wh = DB::table('hss_master')->where('id',$hss_master_id)->get();
    
        return view('hos.store_order',array('delivery_wh'=>$delivery_wh));
    }

    public function profile(){
        return view('hos.profile');
    }

    public function materialData(Request $request){
        $input_data = $request->input_data;
        $input_name = $request->input_name;
        $material_data = MaterialMaster::select('id','nupco_generic_code','nupco_trade_code','customer_code','customer_code_cat','nupco_desc','uom')->where($input_name, $input_data)->get();
        return response()->json(array('data'=>$material_data));
    }

    public function searchData(Request $request){
        $input_data = $request->input_data;
        $input_name = $request->input_name;
        //$search_data = MaterialMaster::select('id',$input_name)->where($input_name,'LIKE',"%{$input_data}%")->get();
        if($input_name == 'nupco_desc'){
            $search_data = DB::table('material_master')->where($input_name,'LIKE',"%{$input_data}%")->select('id',$input_name)->take(50)->get();
        }else{
            $search_data = DB::table('material_master')->where($input_name,'LIKE',"{$input_data}%")->select('id',$input_name)->take(50)->get();
        }
        

        if(count($search_data) > 0){
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach($search_data as $row)
            {
                $output .= '<li>'.$row->$input_name.'</li>';
            }
            $output .= '</ul>';
        }else{
            $output = '';
        }
        echo $output;
    }

    public function addOrder(Request $request){
        $qty_arr = $request->input('qty');
        $nupco_generic_code_arr = $request->input('nupco_generic_code');
        $nupco_trade_code_arr = $request->input('nupco_trade_code');
        $customer_trade_code_arr = $request->input('customer_code');
        $category_arr = $request->input('customer_code_cat');
        $material_desc_arr = $request->input('nupco_desc');
        $uom_arr = $request->input('uom');
        $supplying_plant = $request->input('supplying_plant');
        $hss_master_no = $request->input('hss_master_no');
        $hospital_name = $request->input('hospital_name');
        if(count($qty_arr) > 0){
            $order_data = array();
            $ord_no = '000-000-001';
            $last_ord_id= $last3 = DB::table('order_details')->select('order_id')->orderBy('id', 'DESC')->first();
            if(empty($last_ord_id)){
                $lasts_ord_id = '000-000-000';
            }else{
                $lasts_ord_id=$last_ord_id->order_id;
            }
            
            if($lasts_ord_id!==''){
                $ord_no=str_replace('-', '',$lasts_ord_id);
                $ord_no=$ord_no+1;
                $ord_no=str_pad($ord_no,9,'0',STR_PAD_LEFT);
                $ord_no = implode('-',str_split($ord_no,3));
            }
            $delivery_date = date("Y-m-d", strtotime($request->input('delivery_date')));
            $order_item = 10;
            foreach($qty_arr as $key=>$val) {
                if(!empty($val)){ 
                           
                $order_data[] = array('order_id' => $ord_no,
                                'order_item'=>$order_item,
                                'created_date'=>date('Y-m-d H:i:s'),
                                'last_updated_date'=>date('Y-m-d H:i:s'),
                                'user_id'=>Auth::user()->id,
                                'user'=>Auth::user()->name,
                                'category'=>$category_arr[$key],
                                'nupco_generic_code'=>$nupco_generic_code_arr[$key],
                                'nupco_trade_code'=>$nupco_trade_code_arr[$key],
                                'customer_trade_code'=>$customer_trade_code_arr[$key],
                                'material_desc'=>$material_desc_arr[$key],
                                'qty_ordered'=>$val,
                                'uom'=>$uom_arr[$key],
                                'delivery_date'=>$delivery_date,
                                'supplying_plant'=>$supplying_plant,
                                'hss_master_no'=>$hss_master_no,
                                'hospital_name'=>$hospital_name,
                                'status'=>0 );
                    $order_item = $order_item + 10;
                }
                
            }
            $result = 0;
            if(!empty($order_data)){
                $result = OrderDetail::insert($order_data);
            }
            
            if($result){
                $status = 1;
                $request->session()->flash("message","<div class='col-12 text-center alert alert-success' role='alert'>Request Added Successfully<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden = 'true' >&times; </span></button></div>");
            }else{
                $status = 0;
                $request->session()->flash("message","<div class='col-12 text-center alert alert-danger' role='alert'>Something went wrong.Please try again or contact to admin<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden = 'true' >&times; </span></button></div>");
            }
            return $status;
        }
        
    }

    public function orderDetail($order_id){
        $order_detail = DB::table('order_details as od')
                                        ->join('hss_master as hs','od.hss_master_no','=','hs.hss_master_no')
                                        ->select('od.hss_master_no','od.hospital_name','hs.delivery_wh_name','hs.address','od.id','od.nupco_generic_code','od.nupco_trade_code','od.customer_trade_code','od.category','od.material_desc','od.uom','od.qty_ordered','od.delivery_date','od.created_date','od.status','od.is_deleted',DB::raw("(SELECT count(bl.id) FROM batch_list as bl WHERE bl.order_id = od.id) as batch_count"))
                                        ->where('od.order_id', $order_id)
                                        ->get();

        $pgi_details = DB::table('pgi_details as pd')->select('pd.pgi_id')->where('pd.order_id',$order_id)->first();

        return view('hos.store_order_details',array('order_detail'=>$order_detail,'order_id'=>$order_id,'pgi_details'=>$pgi_details));
    }

    public function orderUpdate(Request $request){

        if($request->has('delete_row')){
            $delete_row_id_arr = $request->input('delete_row');
            foreach($delete_row_id_arr as $key=>$val) {
                DB::table('order_details')
                ->where('id',$val)
                ->update([
                    'is_deleted'=>1,
                ]);
            }
        }
        
        $supplying_plant = $request->input('supplying_plant');
        $hss_master_no = $request->input('hss_master_no');
        $hospital_name = $request->input('hospital_name');
        $delivery_date = date('Y-m-d',strtotime($request->input('delivery_date')));

        $order_primary_id_arr = $request->input('order_primary_id');
        $qty_arr = $request->input('qty');
        $nupco_generic_code_arr = $request->input('nupco_generic_code');
        $nupco_trade_code_arr = $request->input('nupco_trade_code');
        $customer_trade_code_arr = $request->input('customer_code');
        $category_arr = $request->input('customer_code_cat');
        $material_desc_arr = $request->input('nupco_desc');
        $uom_arr = $request->input('uom');

       
        foreach($order_primary_id_arr as $key=>$val){
            DB::table('order_details')
                ->where('id',$val)
                ->update([
                    'qty_ordered' => $qty_arr[$key],
                    'category'=> $category_arr[$key],
                    'nupco_generic_code'=> $nupco_generic_code_arr[$key],
                    'nupco_trade_code'=>$nupco_trade_code_arr[$key],
                    'customer_trade_code'=>$customer_trade_code_arr[$key],
                    'material_desc'=>$material_desc_arr[$key],
                    'uom'=>$uom_arr[$key],
                    'delivery_date'=>$delivery_date,
                    'last_updated_date'=>date("Y-m-d H:i:s"),
                    'last_updated_user'=>Auth::user()->name,
                    'status'=>0
            ]);
        }

        

        if($request->has('new_qty')){
        $new_qty_arr = $request->input('new_qty');
        if(count($new_qty_arr) > 0){
            $order_id = $request->input('order_id');
            $new_order_primary_id_arr = $request->input('new_order_primary_id');
            $new_nupco_generic_code_arr = $request->input('new_nupco_generic_code');
            $new_nupco_trade_code_arr = $request->input('new_nupco_trade_code');
            $new_customer_trade_code_arr = $request->input('new_customer_code');
            $new_category_arr = $request->input('new_customer_code_cat');
            $new_material_desc_arr = $request->input('new_nupco_desc');
            $new_uom_arr = $request->input('new_uom');
           // dd($request);

            $order_item = DB::table('order_details')->select('order_item')->orderBy('order_item','DESC')->where('order_id',$order_id)->first();
            $order_item_val = $order_item->order_item;
            foreach($new_qty_arr as $key=>$val) {
                if(!empty($val)){ 
                    $order_item_val = $order_item_val + 10;
                    $order_data_add[] = array('order_id' => $order_id,
                    'order_item'=>$order_item_val,
                    'created_date'=>date('Y-m-d H:i:s'),
                    'last_updated_date'=>date('Y-m-d H:i:s'),
                    'user_id'=>Auth::user()->id,
                    'user'=>Auth::user()->name,
                    'category'=>$new_category_arr[$key],
                    'nupco_generic_code'=>$new_nupco_generic_code_arr[$key],
                    'nupco_trade_code'=>$new_nupco_trade_code_arr[$key],
                    'customer_trade_code'=>$new_customer_trade_code_arr[$key],
                    'material_desc'=>$new_material_desc_arr[$key],
                    'qty_ordered'=>$val,
                    'uom'=>$new_uom_arr[$key],
                    'delivery_date'=>$delivery_date,
                    'supplying_plant'=>$supplying_plant,
                    'hss_master_no'=>$hss_master_no,
                    'hospital_name'=>$hospital_name,
                    'status'=>0 );
                }
            }

        }
        
            if(!empty($order_data_add)){
                $result = OrderDetail::insert($order_data_add);
            }
        }

        
        return back()->with("message","<div class='col-12 text-center alert alert-success' role='alert'>Request Updated Successfully<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden = 'true' >&times; </span></button></div>");
        
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
