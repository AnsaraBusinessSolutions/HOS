<?php

namespace App\Http\Controllers\Hos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class DepartmentController extends Controller
{
    public function __construct()
    {
        
        $this->middleware('auth');
    }

    /*redirect to department order page */
    public function departmentOrder(){
        $departments = DB::table('departments')->get();
        return view('hos.department_order',array('departments'=>$departments));
    }

     /* Used For searching data from material master table for department order page.
     This function is calling through ajax function*/
     public function departmentSearchData(Request $request){
        $input_data = $request->input_data;
        $input_name = $request->input_name;
        
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

    /*When user select any Nupco code, material,Customer code at that time this function is use and this function give the all details of that code.
    Also here calculating the stock availability.
    This function is calling through ajax function*/
    public function departmentMaterialData(Request $request){
        $input_data = $request->input_data;
        $input_name = $request->input_name;
        $material_data = DB::table('material_master')->select('id','nupco_generic_code','nupco_trade_code','customer_code','customer_code_cat','nupco_desc','uom')->where($input_name, $input_data)->get();
       
        $availability = 0;

        $hss_master_no = Auth::user()->hss_master_no;

        $stock_data = DB::table('stock')->where('plant',$hss_master_no)
                                        ->where('storage_location',$hss_master_no)
                                        ->where('nupco_generic_code',$material_data[0]->nupco_generic_code)
                                        ->groupBy('nupco_generic_code')
                                        ->selectRaw('sum(unrestricted_stock_qty) as total_qty')
                                        ->first();

        $open_qty_data = DB::table('department_order_details')
                        ->where('hss_master_no',$hss_master_no)
                        ->where('nupco_generic_code',$material_data[0]->nupco_generic_code)
                        ->where('is_deleted',0)
                        ->whereIn('status',[0])
                        ->groupBy('nupco_generic_code')
                        ->selectRaw('sum(qty_ordered) as open_qty')
                        ->first();
           
            $total_qty = 0;
            $open_qty = 0;
            if(!empty($stock_data)){
                $total_qty = $stock_data->total_qty;
            }
            if(!empty($open_qty_data)){
                $open_qty =  $open_qty_data->open_qty;
            }
            if($total_qty > $open_qty){
                $availability =  $total_qty - $open_qty;
            }
           
        return response()->json(array('data'=>$material_data,'availability'=>$availability));
    }

    /*Generating the department_order_id and add all the items of order in department order details table.
    This function is calling through ajax function.*/
    public function addDepartmentOrder(Request $request){
        $result = 0;
        if($request->has('qty')){
            $qty_arr = $request->input('qty');
            $nupco_generic_code_arr = $request->input('nupco_generic_code');
            $nupco_trade_code_arr = $request->input('nupco_trade_code');
            $customer_trade_code_arr = $request->input('customer_code');
            $category_arr = $request->input('customer_code_cat');
            $material_desc_arr = $request->input('nupco_desc');
            $uom_arr = $request->input('uom');
            $order_type = $request->input('order_type');
            $item_text = $request->input('item_text');
            $header_text = $request->input('header_text');
            $department_id = $request->input('department_id');

            $hss_master_id = Auth::user()->hss_master_id;
            $hss_data = DB::table('hss_master')->where('id',$hss_master_id)->first();
            
            if(!empty($hss_data)){
                $supplying_plant_code = $hss_data->delivery_warehouse;
                $supplying_plant = $hss_data->delivery_wh_name;
                $sloc_id = $hss_data->sloc_id;
                $hss_master_no = $hss_data->hss_master_no;
            }else{
                $supplying_plant_code = '';
                $supplying_plant = '';
                $sloc_id = '';
                $hss_master_no = '';
            }
          
            if(count($qty_arr) > 0){
                $department_order_data = array();
                $ord_no = '900-000-001';
                $last_ord_id= $last3 = DB::table('department_order_details')->select('department_order_id')->orderBy('department_order_id', 'DESC')->first();
                if(empty($last_ord_id)){
                    $lasts_ord_id = '900-000-000';
                }else{
                    $lasts_ord_id=$last_ord_id->department_order_id;
                }
                
                if($lasts_ord_id!==''){
                    $ord_no=str_replace('-', '',$lasts_ord_id);
                    $ord_no=$ord_no+1;
                    $ord_no=str_pad($ord_no,9,'0',STR_PAD_LEFT);
                    $ord_no = implode('-',str_split($ord_no,3));
                }
                
                foreach($qty_arr as $key=>$val) {
                    if(!empty($val) && !empty($nupco_generic_code_arr[$key])){ 
                            
                    $department_order_data[] = array(
                                    'department_id'=>$department_id,
                                    'department_order_id' => $ord_no,
                                    'created_date'=>date('Y-m-d H:i:s'),
                                    'user_id'=>Auth::user()->id,
                                    'user'=>Auth::user()->name,
                                    'category'=>$category_arr[$key],
                                    'nupco_generic_code'=>$nupco_generic_code_arr[$key],
                                    'nupco_trade_code'=>$nupco_trade_code_arr[$key],
                                    'customer_trade_code'=>$customer_trade_code_arr[$key],
                                    'material_desc'=>$material_desc_arr[$key],
                                    'qty_ordered'=>$val,
                                    'uom'=>$uom_arr[$key],
                                    'supplying_plant_code'=>$supplying_plant_code,
                                    'supplying_plant'=>$supplying_plant,
                                    'sloc_id'=>$sloc_id,
                                    'hss_master_no'=>$hss_master_no,
                                    'order_type'=>$order_type,
                                    'header_text'=>$header_text,
                                    'item_text'=>$item_text[$key],
                                    'status'=>0 );
                        
                    }
                    
                }
                
                if(!empty($department_order_data)){
                    $result = DB::table('department_order_details')->insert($department_order_data);
                }
            }
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

    /*Get All the department order */
    public function departmentOrderList(){
        $user_id = Auth::user()->id;
        $hss_master_no = Auth::user()->hss_master_no;
        
        $all_order = DB::table('department_order_details as dod')
                                ->join('departments as d','dod.department_id','=','d.id')
                                ->where('dod.user_id','=',$user_id)
                                ->where('dod.hss_master_no','=',$hss_master_no)
                                ->orderBy('dod.order_type','ASC')
                                ->orderBy('dod.status','ASC')
                                ->orderBy('dod.department_order_id','DESC')
                                ->groupBy("dod.department_order_id")
                                ->select('d.department_name','dod.department_order_id','dod.supplying_plant','dod.uom','dod.qty_ordered','dod.status','dod.created_date','dod.order_type')
                                ->selectRaw('sum(dod.qty_ordered) as total_qty')
                                ->selectRaw('count(dod.department_order_id) as total_item')
                                ->get();
   
        return view('hos.department_order_list', array('all_order'=>$all_order));
    }

    //Getting all items of order with all the details
    public function departmentOrderDetail($department_order_id){
        $order_detail = DB::table('department_order_details as dod')
                        ->leftjoin('departments as d','dod.department_id','=','d.id')
                        ->leftjoin('stock_consumption as sc','dod.id','=','sc.department_order_main_id')
                        ->where('dod.department_order_id',$department_order_id)
                        ->select('dod.*','d.department_name','sc.batch','sc.mfg_date','sc.expiry_date','sc.qty')
                        ->get();

        return view('hos.department_order_details', array('order_detail'=>$order_detail,'department_order_id'=>$department_order_id));
    }

    //Add stock consumption of department 
    public function addStockConsumption(Request $request){
        
        $result = 0;
        $department_order_main_id = $request->input('department_order_main_id');
        $received_qty = $request->input('received_qty');
        $batch = $request->input('batch');
        $mfg_date = $request->input('mfg_date');
        $expiry_date = $request->input('expiry_date');
        $hss_master_no = Auth::user()->hss_master_no;

        $stock_cons_data = array();
        $dept_main_id_arr = array();
        if(count($received_qty) > 0){
            foreach($received_qty as $key=>$val){
                if($val !='' && $val!= 0){
                    $department_order_data = DB::table('department_order_details as dod')
                                            ->select('dod.nupco_generic_code','dod.nupco_trade_code')
                                            ->where('dod.id',$department_order_main_id[$key])
                                            ->first();

                    if(!empty($department_order_data)){
                        $stock_cons_data[] = array(
                            'department_order_main_id'=>$department_order_main_id[$key],
                            'nupco_generic_code'=>$department_order_data->nupco_generic_code,
                            'trade_code'=>$department_order_data->nupco_trade_code,
                            'plant'=>$hss_master_no,
                            'sloc_id'=>$hss_master_no,
                            'qty'=>$val,
                            'batch'=>$batch[$key],
                            'mfg_date'=>$mfg_date[$key],
                            'expiry_date'=>$expiry_date[$key],
                            'cons_type'=>'Consumption',
                            'created_at'=>date('Y-m-d H:i:s')
                        );

                        $dept_main_id_arr[] = $department_order_main_id[$key];
                    }
                }
            }
        }
        
        if(count($stock_cons_data)>0){
            $result = DB::table('stock_consumption')->insert($stock_cons_data);

            if(count($dept_main_id_arr)>0){
                foreach($dept_main_id_arr as $key=>$val) {
                    DB::table('department_order_details')
                    ->where('id',$val)
                    ->update([
                        'status'=>1,
                    ]);
                }
            }
        }

        if($result){
            $status = 1;
            $request->session()->flash("message","<div class='col-12 text-center alert alert-success' role='alert'>Stock updated Successfully<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden = 'true' >&times; </span></button></div>");
        }else{
            $status = 0;
            $request->session()->flash("message","<div class='col-12 text-center alert alert-danger' role='alert'>Something went wrong.Please try again or contact to admin<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden = 'true' >&times; </span></button></div>");
        }

        return back();
    }

   
}
