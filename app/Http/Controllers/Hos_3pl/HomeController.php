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
        // $all_order = DB::table('order_details as od')
        //                         ->groupBy("od.order_id")
        //                         ->select('od.order_id','od.supplying_plant','od.hospital_name','od.delivery_date','od.uom','od.qty_ordered','od.created_date',DB::raw('group_concat(distinct od.status) as status'))
        //                         ->selectRaw('sum(od.qty_ordered) as total_qty')
        //                         ->selectRaw('count(od.order_id) as total_item')
        //                         ->having('status','2')
        //                         ->orHaving('status','3')
        //                         ->orHaving('status','5')
        //                         ->orHaving('status','7')
        //                         ->orHaving('status','2,3')
        //                         ->orHaving('status','2,3,5')
        //                         ->orHaving('status','3,5')
        //                         ->orHaving('status','2,5')
        //                         ->orHaving('status','2,7')
        //                         ->orHaving('status','3,7')
        //                         ->orHaving('status','5,7')
        //                         ->orHaving('status','2,3,7')
        //                         ->orHaving('status','2,5,7')
        //                         ->orHaving('status','3,5,7')
        //                         ->orderBy('status','ASC')
        //                         ->orderBy('od.order_id','DESC')
        //                         ->get();

//                                 dd($all_order);
//   exit;

        $all_order = DB::table('order_details as od')
                                ->groupBy("od.order_id")
                                ->select('od.order_id','od.supplying_plant','od.hospital_name','od.delivery_date','od.uom','od.qty_ordered','od.created_date',DB::raw('group_concat(distinct od.status) as status'))
                                ->selectRaw('sum(od.qty_ordered) as total_qty')
                                ->selectRaw('count(od.order_id) as total_item')
                                ->whereIn('od.status',[2,3,5,7])
                                ->orderBy('status','ASC')
                                ->orderBy('od.order_id','DESC')
                                ->get();

 
        return view('hos_3pl.home', array('all_order'=>$all_order));
    }

    public function requestOrderDetail($order_id){
        $order_detail = DB::table('order_details as od')
                        ->join('hss_master as hs','od.hss_master_no','=','hs.hss_master_no')
                        ->select('hs.delivery_wh_name','hs.address','od.hss_master_no','od.hospital_name','od.id','od.order_id','od.nupco_generic_code','od.nupco_trade_code','od.customer_trade_code','od.category','od.material_desc','od.uom','od.qty_ordered','od.delivery_date','od.created_date','od.status','od.is_deleted',DB::raw("(SELECT sum(pd.batch_qty) FROM pgi_details as pd WHERE pd.order_main_id = od.id) as dispatch_batch_count"),DB::raw("(SELECT sum(bl.batch_qty) FROM batch_list as bl WHERE bl.order_main_id = od.id) as added_batch_qty"))
                        ->where('od.order_id', $order_id)
                        ->get();

        $status = DB::table('order_details as od')->select(DB::raw('group_concat(distinct od.status) as status'))->where('od.order_id', $order_id)->first();
        
        //$pgi_details = DB::table('pgi_details as pd')->select('pd.pgi_id')->where('pd.order_id',$order_id)->first();
        
        $total_qty = 0;
        foreach ($order_detail as $key=>$value) {
            $total_qty += $value->qty_ordered;
        }
        return view('hos_3pl.request_order_details',array('order_detail'=>$order_detail,'order_id'=>$order_id,'total_qty'=>$total_qty,'status_data'=>$status));
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
        $result = 0;
        $batch_qty_array = $request->input('batch_qty');
        $batch_no_array = $request->input('batch_no');
        $manufacture_date_array = $request->input('manufacture_date');
        $expiry_date_array = $request->input('expiry_date');
        $order_main_id = $request->input('order_main_id');
        $order_id = $request->input('order_id');

        $order_detail = DB::table('order_details')->where('id',$order_main_id)->take(1)->get();
        DB::table('batch_list')->where('order_main_id', $order_main_id)->delete();
        DB::statement('ALTER TABLE batch_list AUTO_INCREMENT = 0');

        if(count($order_detail)>0){
            $nupco_generic_code = $order_detail[0]->nupco_generic_code;
            $nupco_trade_code = $order_detail[0]->nupco_trade_code;
            $customer_trade_code = $order_detail[0]->customer_trade_code;
            $material_desc = $order_detail[0]->material_desc;
            $uom = $order_detail[0]->uom;
            $qty_ordered = $order_detail[0]->qty_ordered;
            $category = $order_detail[0]->category;
        }else{
            $nupco_generic_code = '';
            $nupco_trade_code = '';
            $customer_trade_code = '';
            $material_desc = '';
            $uom = '';
            $qty_ordered = '';
            $category = '';
        }

        $last_batch_details = DB::table('batch_list')->select('batch_code')->orderBy('batch_code', 'DESC')->first();
        if(empty($last_batch_details)){
            $batch_code = 101;
        }else{
            $batch_code = $last_batch_details->batch_code;
        }
        if($request->has('batch_qty')){
        foreach($batch_qty_array as $key=>$val) {
            if(!empty($val) && $order_main_id != ''){ 
                $batch_code = $batch_code+1;
                $batch_data[] = array('order_id' => $order_id,
                                    'order_main_id' => $order_main_id,
                                    'batch_code'=>$batch_code,
                                    'batch_qty' => $val,
                                    'batch_no' => $batch_no_array[$key],
                                    'manufacture_date'=>date('Y-m-d',strtotime($manufacture_date_array[$key])),
                                    'expiry_date'=>date('Y-m-d',strtotime($expiry_date_array[$key])),
                                    'nupco_generic_code' => $nupco_generic_code,
                                    'nupco_trade_code' => $nupco_trade_code,
                                    'customer_trade_code'=>$customer_trade_code,
                                    'material_desc'=>$material_desc,
                                    'uom'=>$uom,
                                    'qty_ordered'=>$qty_ordered,
                                    'category'=>$category,
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s') );
            }
        }
           
            if(!empty($batch_data)){
                $result = DB::table('batch_list')->insert($batch_data);
            }
        }

        if($result){
            $status = 1;
            $request->session()->flash("message","<div class='col-12 text-center alert alert-success' role='alert'>Batch Added Successfully<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden = 'true' >&times; </span></button></div>");
        }else{
            $status = 0;
            $request->session()->flash("message","<div class='col-12 text-center alert alert-danger' role='alert'>Something went wrong.Please try again.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden = 'true' >&times; </span></button></div>");
        }

        return $status;

    }

    public function batchData(Request $request){
        $order_main_id = $request->input('order_main_id');
        $status = $request->input('status');
        $batch_data = array();
        if($order_main_id != ''){
            if($status == 3){
            $batch_data = DB::table('pgi_details')
                                    ->select('batch_qty','batch_no',DB::raw('DATE_FORMAT(manufacture_date, "%m/%d/%Y") as manufacture_date'),DB::raw('DATE_FORMAT(expiry_date, "%m/%d/%Y") as expiry_date'))
                                    ->where('order_main_id',$order_main_id)->get();
            }else{
            $batch_data = DB::table('batch_list')
                                    ->select('batch_qty','batch_no',DB::raw('DATE_FORMAT(manufacture_date, "%m/%d/%Y") as manufacture_date'),DB::raw('DATE_FORMAT(expiry_date, "%m/%d/%Y") as expiry_date'))
                                    ->where('order_main_id',$order_main_id)->get();
            }
        }
        return $batch_data;
    }

    public function orderDispatch(Request $request){
        $order_id = $request->input('order_id');
       
        if($order_id != ''){
           $batch_data =  DB::table('batch_list')->where('order_id',$order_id)->get();
           if(count($batch_data) > 0){
           
            $pgi_no = '500-000-001';
            $last_pgi_id = DB::table('pgi_details')->select('pgi_id')->orderBy('pgi_id', 'DESC')->first();
            if(empty($last_pgi_id)){
                $lasts_pgi_id = '500-000-000';
            }else{
                $lasts_pgi_id=$last_pgi_id->pgi_id;
            }
            
            if($lasts_pgi_id!==''){
                $pgi_no=str_replace('-', '',$lasts_pgi_id);
                $pgi_no=$pgi_no+1;
                $pgi_no=str_pad($pgi_no,9,'0',STR_PAD_LEFT);
                $pgi_no = implode('-',str_split($pgi_no,3));
            }
           
            foreach($batch_data as $key=>$val){
            $pgi_details[] = array(
                            'pgi_id'=>$pgi_no,
                            'batch_qty' => $val->batch_qty,
                            'batch_no' => $val->batch_no,
                            'manufacture_date'=>$val->manufacture_date,
                            'expiry_date' => $val->expiry_date,
                            'order_id' => $order_id,
                            'order_main_id' => $val->order_main_id,
                            'category' => $val->category,
                            'nupco_generic_code' => $val->nupco_generic_code,
                            'nupco_trade_code' => $val->nupco_trade_code,
                            'customer_trade_code'=>$val->customer_trade_code,
                            'material_desc'=>$val->material_desc,
                            'qty_ordered'=>$val->qty_ordered,
                            'uom'=>$val->uom,
                            'delivery_date'=>$request->input('delivery_date'),
                            'supplying_plant'=>$request->input('supplying_plant'),
                            'hss_master_no'=>$request->input('hss_master_no'),
                            'hospital_name'=>$request->input('hospital_name'),
                            'vehicle_no'=>$request->input('vehical_number'),
                            'created_at'=>date('Y-m-d H:i:s'));
                
                $order_main_id_arr[] = $val->order_main_id;
               }
               
                if(!empty($pgi_details)){
                    $result = DB::table('pgi_details')->insert($pgi_details);
                   
                    
                    if(count($order_main_id_arr) > 0){
                    foreach($order_main_id_arr as $key=>$val){
                        $dispatch_qty = DB::table('pgi_details as pg')
                        ->where('pg.order_main_id',$val)
                        ->select('pg.qty_ordered')
                        ->selectRaw('sum(pg.batch_qty) as total_dispatch_qty')
                        ->first();

                        if(!empty($dispatch_qty)){
                            if($dispatch_qty->total_dispatch_qty < $dispatch_qty->qty_ordered){
                                $order_status = 7;
                            }elseif($dispatch_qty->total_dispatch_qty == $dispatch_qty->qty_ordered){
                                $order_status = 5;
                            }

                            DB::table('order_details')
                            ->where('id',$val)
                            ->update([
                                'status' => $order_status,
                                'last_updated_date'=>date("Y-m-d H:i:s"),
                                'last_updated_user'=>auth()->guard('hos3pl')->user()->name
                                ]);

                            DB::table('pgi_details')
                            ->where('order_main_id',$val)
                            ->update([
                                'pgi_status' => $order_status,
                                ]);
                        }
                    }
                }

                    $total_order_qty_sum = DB::table('order_details as od')
                    ->where('od.order_id',$order_id)
                    ->selectRaw('sum(od.qty_ordered) as total_order')
                    ->first();

                    $total_dispatch_qty_sum = DB::table('pgi_details as pg')
                    ->where('pg.order_id',$order_id)
                    ->selectRaw('sum(pg.batch_qty) as total_dispatch')
                    ->first();
                
                    if(!empty($total_order_qty_sum) && !empty($total_dispatch_qty_sum)){
                        if($total_order_qty_sum->total_order == $total_dispatch_qty_sum->total_dispatch){
                            DB::table('order_details')
                            ->where('order_id',$order_id)
                            ->update([
                                'status' => 3,
                                'last_updated_date'=>date("Y-m-d H:i:s"),
                                'last_updated_user'=>auth()->guard('hos3pl')->user()->name
                                ]);

                            DB::table('pgi_details')
                            ->where('order_id',$order_id)
                            ->update([
                                'pgi_status' => 3,
                                ]);
                        }
                    }
                    
                    DB::table('batch_list')->where('order_id', $order_id)->delete();
                    DB::statement('ALTER TABLE batch_list AUTO_INCREMENT = 0');
                }
           }
        }
        if($request->has('redirect_page_name')){
            return redirect()->route('hos3pl.open.order');
        }
        return redirect()->route('hos3pl.home');
        // return back();
    }

    public function openOrder()
    {
        $user_id = auth()->guard('hos3pl')->user()->id;
        // $all_order = DB::table('order_details as od')
        //                         ->groupBy("od.order_id")
        //                         ->select('od.order_id','od.supplying_plant','od.hospital_name','od.delivery_date','od.uom','od.qty_ordered','od.created_date',DB::raw('group_concat(distinct od.status) as status'))
        //                         ->selectRaw('sum(od.qty_ordered) as total_qty')
        //                         ->selectRaw('count(od.order_id) as total_item')
        //                         ->having('status','2')
        //                         ->orHaving('status','5')
        //                         ->orHaving('status','7')
        //                         ->orHaving('status','2,3')
        //                         ->orHaving('status','2,3,5')
        //                         ->orHaving('status','3,5')
        //                         ->orHaving('status','2,5')
        //                         ->orHaving('status','2,7')
        //                         ->orHaving('status','3,7')
        //                         ->orHaving('status','5,7')
        //                         ->orHaving('status','2,3,7')
        //                         ->orHaving('status','2,5,7')
        //                         ->orHaving('status','3,5,7')
        //                         ->orderBy('status','ASC')
        //                         ->orderBy('od.order_id','DESC')
        //                         ->get();

        $all_order = DB::table('order_details as od')
                                ->groupBy("od.order_id")
                                ->select('od.order_id','od.supplying_plant','od.hospital_name','od.delivery_date','od.uom','od.qty_ordered','od.created_date',DB::raw('group_concat(distinct od.status) as status'))
                                ->selectRaw('sum(od.qty_ordered) as total_qty')
                                ->selectRaw('count(od.order_id) as total_item')
                                ->whereIn('od.status',[2,5,7])
                                ->orderBy('status','ASC')
                                ->orderBy('od.order_id','DESC')
                                ->get();
   
        return view('hos_3pl.open_order', array('all_order'=>$all_order));
    }

    public function openOrderDetail($order_id)
    {
        $order_detail = DB::table('order_details as od')
        ->join('hss_master as hs','od.hss_master_no','=','hs.hss_master_no')
        ->select('hs.delivery_wh_name','hs.address','od.hss_master_no','od.hospital_name','od.id','od.order_id','od.nupco_generic_code','od.nupco_trade_code','od.customer_trade_code','od.category','od.material_desc','od.uom','od.qty_ordered','od.delivery_date','od.created_date','od.status','od.is_deleted',DB::raw("(SELECT sum(pd.batch_qty) FROM pgi_details as pd WHERE pd.order_main_id = od.id) as dispatch_batch_count"),DB::raw("(SELECT sum(bl.batch_qty) FROM batch_list as bl WHERE bl.order_main_id = od.id) as added_batch_qty"))
        ->where('od.order_id', $order_id)
        ->whereIn('od.status', [2,7])
        ->get();

        $status = DB::table('order_details as od')->select(DB::raw('group_concat(distinct od.status) as status'))->where('od.order_id', $order_id)->first();

        //$pgi_details = DB::table('pgi_details as pd')->select('pd.pgi_id')->where('pd.order_id',$order_id)->first();

        $total_qty = 0;
        foreach ($order_detail as $key=>$value) {
        $total_qty += $value->qty_ordered;
        }
        return view('hos_3pl.open_order_details',array('order_detail'=>$order_detail,'order_id'=>$order_id,'total_qty'=>$total_qty,'status_data'=>$status));
    }

    public function displayOrder()
    {
        $user_id = auth()->guard('hos3pl')->user()->id;
        $all_order = DB::table('pgi_details as pd')
                                ->groupBy("pd.order_id")
                                ->select('pd.order_id','pd.supplying_plant','pd.hospital_name','pd.delivery_date','pd.uom','pd.qty_ordered','pd.created_at')
                                ->selectRaw('sum(pd.batch_qty) as total_batch_qty')
                                ->selectRaw("count(DISTINCT(pd.id)) as total_item")
                                ->orderBy('pd.order_id','DESC')
                                ->get();
   
        return view('hos_3pl.display_order', array('all_order'=>$all_order));
    }

    public function displayOrderDetail($order_id)
    {
        $order_detail = DB::table('pgi_details as pd')
        ->join('hss_master as hs','pd.hss_master_no','=','hs.hss_master_no')
        ->join('order_details as od','pd.order_main_id','=','od.id')
        ->select('pd.batch_qty','od.created_date','hs.delivery_wh_name','hs.address','pd.hss_master_no','pd.hospital_name','pd.id','pd.pgi_id','pd.order_id','pd.order_main_id','pd.nupco_generic_code','pd.nupco_trade_code','pd.customer_trade_code','pd.category','pd.material_desc','pd.uom','pd.qty_ordered','pd.delivery_date','pd.created_at','pd.batch_qty','pd.batch_no','pd.manufacture_date','pd.expiry_date')
        ->selectraw('sum(pd.batch_qty) as batch_qty')
        ->where('pd.order_id', $order_id)
        ->groupBy(DB::raw("pd.order_main_id,pd.pgi_id"))
        ->get();

        $status = DB::table('order_details as od')->select(DB::raw('group_concat(distinct od.status) as status'))->where('od.order_id', $order_id)->first();

        $total_qty = 0;
        foreach ($order_detail as $key=>$value) {
        $total_qty += $value->qty_ordered;
        }
        return view('hos_3pl.display_order_details',array('order_detail'=>$order_detail,'order_id'=>$order_id,'total_qty'=>$total_qty,'status_data'=>$status));
    }


    public function displayBatchData(Request $request){
        $order_main_id = $request->input('order_main_id');
        $status = $request->input('status');
        $pgi_id = $request->input('pgi_id');
        $batch_data = array();
        $batch_data = DB::table('pgi_details')
                    ->select('batch_qty','batch_no',DB::raw('DATE_FORMAT(manufacture_date, "%m/%d/%Y") as manufacture_date'),DB::raw('DATE_FORMAT(expiry_date, "%m/%d/%Y") as expiry_date'))
                    ->where('order_main_id',$order_main_id)
                    ->where('pgi_id',$pgi_id)
                    ->get();
           
        return $batch_data;
    }


}
