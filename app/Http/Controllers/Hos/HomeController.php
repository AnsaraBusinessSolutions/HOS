<?php

namespace App\Http\Controllers\Hos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Hos\MaterialMaster;
use App\Model\Hos\OrderDetail;
use Auth;
use DB;
ini_set('memory_limit', '-1');

class HomeController extends Controller
{
    public function index()
    {
        return view('hos.home');
    }

    public function storeOrder()
    {
        return view('hos.store_order');
    }

    public function profile(){
        return view('hos.profile');
    }

    public function materialData(Request $request){
        $input_data = $request->input_data;
        $input_name = $request->input_name;
        $material_data = MaterialMaster::select('id','nupco_material_generic_code','customer_bp','material_description','buom')->where($input_name, $input_data)->get();
        return response()->json(array('data'=>$material_data));
    }

    public function searchData(Request $request){
        $input_data = $request->input_data;
        $input_name = $request->input_name;
        //$search_data = MaterialMaster::select('id',$input_name)->where($input_name,'LIKE',"%{$input_data}%")->get();
        $search_data = MaterialMaster::where($input_name,'LIKE',"%{$input_data}%")->select('id',$input_name)->get();

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
        $qty_array = $request->input('qty');
        $material_master_id_array = $request->input('material_master_id');
    
        if(count($qty_array) > 0){
            $order_data = array();
            $ord_no = '000-000-001';
            $last_ord_id= $last3 = DB::table('order_details')->select('order_code')->orderBy('id', 'DESC')->first();
            if(empty($last_ord_id)){
                $lasts_ord_id = '000-000-000';
            }else{
                $lasts_ord_id=$last_ord_id->order_code;
            }
            
            if($lasts_ord_id!==''){
                $ord_no=str_replace('-', '',$lasts_ord_id);
                $ord_no=$ord_no+1;
                $ord_no=str_pad($ord_no,9,'0',STR_PAD_LEFT);
                $ord_no = implode('-',str_split($ord_no,3));
            }
            foreach($qty_array as $key=>$val) {
                if(!empty($val)){ 
                           
                    $order_data[] = array('order_code' => $ord_no,
                                        'material_master_id' => $material_master_id_array[$key],
                                        'user_id'=>Auth::user()->id,
                                        'qty'=>$val,
                                        'status'=>0 );
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
}
