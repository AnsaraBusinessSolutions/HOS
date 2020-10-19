<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ApiController extends Controller
{
    public function AddStock(Request $request){
        $wsdl_link = $request->input('wsdl_link');
        $user_name = $request->input('user_name');
        $pass_word = $request->input('pass_word');
        $soap_header = $request->input('soap_header');
        $parameters = $request->input('parameters');
        
        $data = array(
        "wsdl_link" => $wsdl_link,
        "user_name" => $user_name,
        "pass_word" => $pass_word,
        "soap_header" => $soap_header,
        "parameters" =>$parameters
        );

        $data_string = json_encode($data);
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
               
                $data = array(
                    'customer'=>$val->ZKUNNR,
                    'nupco_generic_code'=>$val->MATNR,
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
                    'expiry_date'=>$val->VFDAT);

                $check_stock_available = DB::table('stock')
                                            ->where($data)
                                            ->select('id')
                                            ->first();
                                          
                if(empty($check_stock_available)){
                    $stock_data[] = array(
                    'customer'=>$val->ZKUNNR,
                    'nupco_generic_code'=>$val->MATNR,
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
            
           if(count($stock_data) > 0){
              $result = DB::table('stock')->insert($stock_data);
                if($result){
                echo 'Stock inserted successfully';
                }
           }else{
                echo 'Stock data already inserted';
           }
          
        }
        
    }

}
