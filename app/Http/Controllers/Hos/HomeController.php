<?php

namespace App\Http\Controllers\Hos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Config;
ini_set('memory_limit', '-1');

class HomeController extends Controller
{
    public function __construct()
    {
        
        $this->middleware('auth');
    }


    /*This function is use for geting all the orders according the hospital users with the status. */
    public function index()
    {     
        $user_id = Auth::user()->id;
        $all_order = DB::table('order_details as od')
                                ->where('od.user_id','=',$user_id)
                                ->orderBy('od.order_type','ASC')
                                ->orderBy('od.status','ASC')
                                ->orderBy('od.order_id','DESC')
                                ->groupBy("od.order_id")
                                ->select('od.order_id','od.supplying_plant','od.delivery_date','od.uom','od.qty_ordered','od.status','od.created_date','od.order_type')
                                ->selectRaw('sum(od.qty_ordered) as total_qty')
                                ->selectRaw('count(od.order_id) as total_item')
                                ->get();
   
        return view('hos.home', array('all_order'=>$all_order));
    }

    /*redirect to the store order page with hospital related details. */
    public function storeOrder()
    {
        $hss_master_id = Auth::user()->hss_master_id;
        $delivery_wh = DB::table('hss_master')->where('id',$hss_master_id)->get();
    
        return view('hos.store_order',array('delivery_wh'=>$delivery_wh));
    }

    /*This function is used for Displaying the profile page. */
    public function profile(){
        return view('hos.profile');
    }

    /*When user select any Nupco code, material,Customer code at that time this function is use and this function give the all details of that code.
    Also here calculating the stock availability.
    This function is calling through ajax function*/
    public function materialData(Request $request){
        $input_data = $request->input_data;
        $input_name = $request->input_name;
        $material_data = DB::table('material_master')->select('id','nupco_generic_code','nupco_trade_code','customer_code','customer_code_cat','nupco_desc','uom')->where($input_name, $input_data)->get();
       
        $availability = 0;

        $hss_master_id = Auth::user()->hss_master_id;
        $hss_data = DB::table('hss_master')->where('id',$hss_master_id)->first();
        

            $plant = $hss_data->delivery_warehouse;
            $storage_location = $hss_data->sloc_id;

             //call soap api
            $parameters = 'I_LGORT='.$storage_location.',I_WERKS='.$plant;
            $wsdl_link = 'http://saprd1ap1.nupco.com:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zmm_whs_stock_srvc/300/zmm_whs_stock_srvc/zmm_whs_stock_srvc?sap-client=300';
            $input_arr = array(
                "wsdl_link" => $wsdl_link,
                "user_name" => Config::get('constants.soap_api_username'),
                "pass_word" => Config::get('constants.soap_api_password'),
                "soap_header" => 'ZMM_WHS_STOCK_INTF',
                "parameters" =>$parameters
                );
            $api_response = $this->AddStockSoapApi($input_arr);
            
            $stock_data = DB::table('stock')->where('plant',$plant)
                                            ->where('storage_location',$storage_location)
                                            ->where('nupco_generic_code',$material_data[0]->nupco_generic_code)
                                            ->groupBy('nupco_generic_code')
                                            ->selectRaw('sum(unrestricted_stock_qty) as total_qty')
                                            ->first();
            $open_qty_data = DB::table('order_details')
                            ->where('supplying_plant_code',$plant)
                            ->where('sloc_id',$storage_location)
                            ->where('nupco_generic_code',$material_data[0]->nupco_generic_code)
                            ->where('is_deleted',0)
                            ->whereIn('status',[0,2])
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

    /* Used For searching data from material master table in store order page.
     This function is calling through ajax function*/
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

    /*Generating the order_id and add all the items of order in order details table.
    This function is calling through ajax function.*/
    public function addOrder(Request $request){
        $result = 0;
        if($request->has('qty')){
            $qty_arr = $request->input('qty');
            $nupco_generic_code_arr = $request->input('nupco_generic_code');
            $nupco_trade_code_arr = $request->input('nupco_trade_code');
            $customer_trade_code_arr = $request->input('customer_code');
            $category_arr = $request->input('customer_code_cat');
            $material_desc_arr = $request->input('nupco_desc');
            $uom_arr = $request->input('uom');
            $supplying_plant_code = $request->input('supplying_plant_code');
            $supplying_plant = $request->input('supplying_plant');
            $sloc_id = $request->input('sloc_id');
            $hss_master_no = $request->input('hss_master_no');
            $hospital_name = $request->input('hospital_name');
            $order_type = $request->input('order_type');
            $item_text = $request->input('item_text');
            $header_text = $request->input('header_text');
            if(count($qty_arr) > 0){
                $order_data = array();
                $ord_no = '000-000-001';
                $last_ord_id= $last3 = DB::table('order_details')->select('order_id')->orderBy('order_id', 'DESC')->first();
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
                    if(!empty($val) && !empty($nupco_generic_code_arr[$key])){ 
                            
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
                                    'supplying_plant_code'=>$supplying_plant_code,
                                    'supplying_plant'=>$supplying_plant,
                                    'sloc_id'=>$sloc_id,
                                    'hss_master_no'=>$hss_master_no,
                                    'hospital_name'=>$hospital_name,
                                    'order_type'=>$order_type,
                                    'header_text'=>$header_text,
                                    'item_text'=>$item_text[$key],
                                    'status'=>0 );
                        $order_item = $order_item + 10;
                    }
                    
                }
                
                if(!empty($order_data)){
                    $result = DB::table('order_details')->insert($order_data);
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

    /*Displaying particular order details using order id.
    Also Here getting the hospital related details.*/
    public function orderDetail($order_id){
        $hss_master_id = Auth::user()->hss_master_id;
        $hss_data = DB::table('hss_master')->where('id',$hss_master_id)->first();

        $plant = $hss_data->delivery_warehouse;
        $storage_location = $hss_data->sloc_id;

        $order_detail = DB::table('order_details as od')
                                        ->join('hss_master as hs','od.hss_master_no','=','hs.hss_master_no')
                                        ->select('od.hss_master_no','od.hospital_name','od.order_type','hs.delivery_wh_name','hs.delivery_warehouse','hs.address','hs.sloc_id','od.id','od.nupco_generic_code','od.nupco_trade_code','od.customer_trade_code','od.category','od.material_desc','od.uom','od.qty_ordered','od.delivery_date','od.created_date','od.status','od.header_text','od.item_text','od.is_deleted',DB::raw("(SELECT count(bl.id) FROM batch_list as bl WHERE bl.order_id = od.id) as batch_count"))
                                        ->selectRaw(DB::raw("(SELECT (sum(sq.unrestricted_stock_qty)) FROM stock as sq WHERE sq.storage_location = '$storage_location' AND sq.plant='$plant' AND sq.nupco_generic_code=od.nupco_generic_code limit 1) as unrestricted_stock_qty"))
                                        ->where('od.order_id', $order_id)
                                        ->get();

        $pgi_details = DB::table('pgi_details as pd')->select('pd.pgi_id')->where('pd.order_id',$order_id)->first();

        return view('hos.store_order_details',array('order_detail'=>$order_detail,'order_id'=>$order_id,'pgi_details'=>$pgi_details,'plant'=>$plant,'storage_location'=>$storage_location));
    }

    /*This function is use for order delete,update and add new item in perticular order using the order id. */
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
        
        $supplying_plant_code = $request->input('supplying_plant_code');
        $supplying_plant = $request->input('supplying_plant');
        $sloc_id =$request->input('sloc_id');
        $hss_master_no = $request->input('hss_master_no');
        $hospital_name = $request->input('hospital_name');
        $order_type = $request->input('order_type');
        $delivery_date = date('Y-m-d',strtotime($request->input('delivery_date')));
        $header_text = $request->input('header_text');

        $order_primary_id_arr = $request->input('order_primary_id');
        $qty_arr = $request->input('qty');
        $nupco_generic_code_arr = $request->input('nupco_generic_code');
        $nupco_trade_code_arr = $request->input('nupco_trade_code');
        $customer_trade_code_arr = $request->input('customer_code');
        $category_arr = $request->input('customer_code_cat');
        $material_desc_arr = $request->input('nupco_desc');
        $uom_arr = $request->input('uom');
        $item_text_arr = $request->input('item_text');
        $old_qty_arr = $request->input('old_qty');
        $old_nupco_generic_code_arr = $request->input('old_nupco_generic_code');
       
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
                    'header_text'=>$header_text,
                    'item_text'=>$item_text_arr[$key],
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
            $new_item_text_arr = $request->input('new_item_text');

            $order_item = DB::table('order_details')->select('order_item')->orderBy('order_item','DESC')->where('order_id',$order_id)->first();
            $order_item_val = $order_item->order_item;
            foreach($new_qty_arr as $key=>$val) {
                if(!empty($val) && !empty($new_nupco_generic_code_arr[$key])){ 
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
                    'supplying_plant_code'=>$supplying_plant_code,
                    'supplying_plant'=>$supplying_plant,
                    'sloc_id'=>$sloc_id,
                    'hss_master_no'=>$hss_master_no,
                    'hospital_name'=>$hospital_name,
                    'order_type'=>$order_type,
                    'header_text'=>$header_text,
                    'item_text'=>$new_item_text_arr[$key],
                    'status'=>0 );
                }
            }

        }
        
            if(!empty($order_data_add)){
                $result = DB::table('order_details')->insert($order_data_add);
            }
        }

        return back()->with("message","<div class='col-12 text-center alert alert-success' role='alert'>Request Updated Successfully<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden = 'true' >&times; </span></button></div>");
        
    }

    /*This function is use for displaying the batch details of the items. */
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

    /*User for the displaying the stock report for inventory menu*/
    public function stockReport(){
        return view('hos.stock_report');
    }

    /*Search stock according the supplying plant,nupco code and description.
    Make tbody string and return the encoded string.
    This function is calling through ajax function.*/
    public function searchStock(Request $request){
        $nupco_generic_code =$request->input('nupco_generic_code');
        $plant =$request->input('plant');
        $nupco_desc =$request->input('nupco_desc');

        //call soap api
        $hss_master_id = Auth::user()->hss_master_id;
        $hss_data = DB::table('hss_master')->select('sloc_id')->where('id',$hss_master_id)->first();
        $storage_location = $hss_data->sloc_id;

        $parameters = 'I_LGORT='.$storage_location.',I_WERKS='.$plant;
        $wsdl_link = 'http://saprd1ap1.nupco.com:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zmm_whs_stock_srvc/300/zmm_whs_stock_srvc/zmm_whs_stock_srvc?sap-client=300';
        $input_arr = array(
            "wsdl_link" => $wsdl_link,
            "user_name" => Config::get('constants.soap_api_username'),
            "pass_word" => Config::get('constants.soap_api_password'),
            "soap_header" => 'ZMM_WHS_STOCK_INTF',
            "parameters" =>$parameters
            );
        $api_response = $this->AddStockSoapApi($input_arr);
            
        $where_arr = array();
        if($plant != ''){
            $where_arr['plant'] = $plant;
        }
        
        $stock_data = DB::table('stock')
        ->where($where_arr)
        ->where('nupco_generic_code',$nupco_generic_code)
        ->where('nupco_desc','like','%'.$nupco_desc.'%')
        ->get();

        $result['data'] = '';
        if(!empty($stock_data)){
            foreach($stock_data as $key=>$val){
            $result['data'] .= "<tr>
                        <td>".$val->plant."</td>
                        <td>".$val->storage_location."</td>
                        <td>".$val->nupco_generic_code."</td>
                        <td>".$val->nupco_trade_code."</td>
                        <td>".$val->customer_trade_code."</td>
                        <td>".$val->nupco_desc."</td>
                        <td>".$val->unrestricted_stock_qty."</td>
                        <td>".$val->vendor_batch."</td>
                        <td>".$val->uom."</td>
                        <td>".$val->mfg_date."</td>
                        <td>".$val->expiry_date."</td>
                    </tr>";
            }
        }
        $result['api_response'] = $api_response;
        echo json_encode($result);

    }

    /*Add stock data using the soap api */
    public function AddStockSoapApi($input_arr){
    if(!empty($input_arr)){
        $response = '';
        $data_string = json_encode($input_arr);
        //print_r($data_string);exit;
       $url="https://tfms.nupco.com/nupco_service/api.php";
        $curl_timeout = 5;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string))
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $curl_timeout);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res);

        if(!empty($res)){
            $stock_details_arr = $res->O_WH_STOCK->DETAILS->item;
            $stock_data = array();
            foreach($stock_details_arr as $key=>$val){
                $nupco_generic_code = ltrim($val->MATNR, '0');
                $data = array(
                    'customer'=>$val->ZKUNNR,
                    'nupco_generic_code'=>$nupco_generic_code,
                   // 'nupco_trade_code'=>$val->BWTAR,
                   // 'customer_trade_code'=>$val->ZCTMATNR,
                   // 'nupco_desc'=>$val->ZNTCDES,
                    'plant'=>$val->WERKS,
                    'storage_location'=>$val->LGORT,
                   // 'unrestricted_stock_qty'=>$val->CLABS,
                    'vendor_batch'=>$val->LICHA,
                   // 'uom'=>$val->MEINS,
                    'batch'=>$val->CHARG,
                   // 'map'=>$val->VERPR,
                   // 'stock_value'=>$val->STOCKV,
                   // 'return_stock'=>$val->CRETM,
                    'mfg_date'=>$val->HSDAT,
                    'expiry_date'=>$val->VFDAT);

                $check_stock_available = DB::table('stock')
                                            ->where($data)
                                            ->select('id')
                                            ->first();
                        //print_r($check_stock_available);                  
                if(empty($check_stock_available)){
                    $stock_data[] = array(
                    'customer'=>$val->ZKUNNR,
                    'nupco_generic_code'=>$nupco_generic_code,
                    'nupco_trade_code'=>$val->BWTAR,
                    'customer_trade_code'=>$val->ZCTMATNR,
                    'nupco_desc'=>$val->ZNTCDES,
                    'plant'=>$val->WERKS,
                    'storage_location'=>$val->LGORT,
                    'unrestricted_stock_qty'=>$val->CLABS,
                    'vendor_batch'=>$val->LICHA,
                    'uom'=>$val->MEINS,
                    'batch'=>$val->CHARG,
                    'map'=>$val->VERPR,
                    'stock_value'=>$val->STOCKV,
                    'return_stock'=>$val->CRETM,
                    'mfg_date'=>$val->HSDAT,
                    'expiry_date'=>$val->VFDAT,
                    'created_at'=>date('Y-m-d H:i:s'));
                }
            }
            // print_r($stock_data);
            // exit;
           if(count($stock_data) > 0){
              $result = DB::table('stock')->insert($stock_data);
           }
            $response = true; 
        }
    }else{
        $response = false;
    }
     return $response;
        
    }

    
}
