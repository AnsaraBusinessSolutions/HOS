<?php

namespace App\Http\Controllers\Hos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Hos\MaterialMaster;
use DB;
ini_set('memory_limit', '-1');

class HomeController extends Controller
{
    
    public function index()
    {
        return view('hos.home');
    }

    public function profile(){
        return view('hos.profile');
    }

    public function materialData(Request $request){
     $input_data = $request->input_data;
     $input_name = $request->input_name;
     if($input_name == 'nupco_material_generic_code'){
        $material = MaterialMaster::select('id','nupco_material_generic_code','customer_bp','material_description','buom')->where('nupco_material_generic_code', $input_data)->get();
     }else if($input_name == 'customer_bp'){
        $material = MaterialMaster::select('id','nupco_material_generic_code','customer_bp','material_description','buom')->where('customer_bp', $input_data)->get();
     }else if($input_name == 'material_description'){
        $material = MaterialMaster::select('id','nupco_material_generic_code','customer_bp','material_description','buom')->where('material_description', $input_data)->get();
     }
     return response()->json(array('data'=>$material));
    }

    public function addOrder(Request $request){
       
        $arr = array();
        $OrderDetail = new OrderDetail();
         $details = [
            $serializedArr = serialize($arr),
            $OrderDetail->order_code = $request->nupco_material_generic_code,
            $OrderDetail->material_master_id = $request->customer_bp,
            $OrderDetail->user_id = Auth::user()->id,
            $OrderDetail->qty = $request->qty,
            $OrderDetail->status = 'Requested',
         ];
         array_push($arr,$details);
         print_r($arr);
         print_r($details);
         if($OrderDetail->save())
         {
             return redirect()->back()->with('success','Kërkesa u dërgua me sukses :)');
         }
         else
         {
             return redirect()->back()->with('error','Kërkesa nuk u dërgua :( ');
         }
    }
}
