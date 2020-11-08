<?php

namespace App\Http\Controllers\Hos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Config;

class SalesOrderController extends Controller
{
    /*This function is use for sales order. */
    public function salesOrder()
    {     
        $pending_order_data = DB::select('SELECT * FROM sales_order_log WHERE id IN (SELECT MAX(id) FROM sales_order_log where status IN ("pending","fail") GROUP BY hss_order_id)');
        // dd($pending_order_data);
        foreach($pending_order_data as $key=>$pending_order_val){
            $sales_org_data = DB::table('sales_org')->where('hss_master_no',$pending_order_val->hss_master_no)->first();
            $order_details = DB::table('order_details')->where('order_id',$pending_order_val->hss_order_id)->get();
            $miniostry_data = DB::table('hss_master as hm')->join('ministry_logo as ml','hm.ministry','=','ml.id')
                            ->where('hm.hss_master_no',$pending_order_val->hss_master_no)->select('ml.*')->first();

            // $parameters ='SALESDOCUMENTIN='.$pending_order_val->sap_id.
            //             ',DOC_TYPE='.$sales_org_data->so_doc.
            //             ',SALES_ORG='.$sales_org_data->sales_org.
            //             ',DISTR_CHAN='.$sales_org_data->dist_channel.
            //             ',DIVISION='.$sales_org_data->division.
            //             ',REQ_DATE_H='.$order_details[0]->delivery_date.
            //             ',PURCH_DATE='.$order_details[0]->created_date.
            //             ',PURCH_NO_C=HOS ORDER
            //             ,CURRENCY=SAR
            //             ,ITM_NUMBER='.$order_details[0]->order_item.
            //             ',MATERIAL='.$order_details[0]->nupco_generic_code.
            //             ',BATCH=""
            //             ,PLANT='.$order_details[0]->supplying_plant_code.
            //             ',STORE_LOC='.$order_details[0]->sloc_id.
            //             ',TARGET_QTY='.$order_details[0]->qty_ordered.
            //             ',SALES_UNIT='.$order_details[0]->uom.
            //             ',ZZMN=""
            //             ,ZZAS=""
            //             ,ZZCO=""
            //             ,PARTN_ROLE='.$miniostry_data->customer_name.
            //             ',PARTN_NUMB='.$miniostry_data->customer_id;

            // $order_arr[] = array(
            //             'ITM_NUMBER'=>$order_details[0]->order_item,
            //             'MATERIAL'=>$order_details[0]->nupco_generic_code,
            //             'BATCH'=>'',
            //             'PLANT'=>$order_details[0]->supplying_plant_code,
            //             'STORE_LOC'=>$order_details[0]->sloc_id,
            //             'TARGET_QTY'=>$order_details[0]->qty_ordered,
            //             'SALES_UNIT'=>$order_details[0]->uom,
            // );

            //             $parameters = array('SALESDOCUMENTIN' => $pending_order_val->sap_id,
            //             'DOC_TYPE'=>$sales_org_data->so_doc,
            //             'SALES_ORG'=>$sales_org_data->sales_org,
            //             'DISTR_CHAN'=>$sales_org_data->dist_channel,
            //             'DIVISION'=>$sales_org_data->division,
            //             'REQ_DATE_H'=>$order_details[0]->delivery_date,
            //             'PURCH_DATE'=>$order_details[0]->created_date,
            //             'PURCH_NO_C'=>'HOS ORDER',
            //             'CURRENCY'=>'SAR',
            //             'I_T_ITEM'=>$order_arr,
                        
            //             'ZZMN'=>'',
            //             'ZZAS'=>'',
            //             'ZZCO'=>'',
            //             'PARTN_ROLE'=>$miniostry_data->customer_name,
            //             'PARTN_NUMB'=>$miniostry_data->customer_id);

                        $I_HEADER = array(
                            "SALESDOCUMENTIN"=>"5000001145",
                            "DOC_TYPE"=>"ZPSO",
                            "SALES_ORG"=>"NP02",
                            "DISTR_CHAN"=>"E1",
                            "DIVISION"=>"00",
                            "REQ_DATE_H"=>"20201102",
                            "PURCH_DATE"=>"20201102",
                            "PURCH_NO_C"=>"BAPI VARIANT1",
                            "CURRENCY"=>"SAR",
                            "MAWARID_NO"=>"12345",
                            "ORD_REASON"=>"ZN5"
                          );    
          $I_T_ITEM = array("item"=>
                                  array(
                                        "ITM_NUMBER"=>"000010",
                                        "MATERIAL"=>"5110150300205",
                                        "BATCH"=>"",
                                        "PLANT"=>"E1C2",
                                        "STORE_LOC"=>"E000",
                                        "TARGET_QTY"=>"1.00",
                                        "SALES_UNIT"=>"TUB",
                                        "ZZMN"=>"BAYER",
                                        "ZZAS"=>"20001",
                                        "ZZCO"=>"SA",
                                        "DLV_PRIO"=>"99"
                                  ),
                                  "item"=>
                                  array(
                                        "ITM_NUMBER"=>"000010",
                                        "MATERIAL"=>"5110150300205",
                                        "BATCH"=>"",
                                        "PLANT"=>"E1C2",
                                        "STORE_LOC"=>"E000",
                                        "TARGET_QTY"=>"13.00",
                                        "SALES_UNIT"=>"TUB",
                                        "ZZMN"=>"BAYER",
                                        "ZZAS"=>"20001",
                                        "ZZCO"=>"SA",
                                          "DLV_PRIO"=>"99"
                                  )
                          );
          $I_T_PARTNERS = array("item"=>
                                  array(
                                        "PARTN_ROLE"=>"SP",
                                        "PARTN_NUMB"=>"10005"
                              ),"item"=>
                                  array(
                                        "PARTN_ROLE"=>"SH",
                                        "PARTN_NUMB"=>"10005"
                                      )
                          );
          $parameters = array("I_SO_DET"=>
                                  array(
                                      "I_HEADER"=>$I_HEADER,
                                      "I_T_ITEM"=>$I_T_ITEM,
                                      "I_T_PARTNERS"=>$I_T_PARTNERS
                                  )
                          );
            echo '<pre>';
            print_r($parameters);
            exit;
            $wsdl_link = 'http://saprd1ap1.nupco.com:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zsd_hos_so_create_srvc/300/zsd_hos_so_create_srvc/zsd_hos_so_create_srvc?sap-client=300';
            $input_arr = array(
                "wsdl_link" => $wsdl_link,
                "user_name" => Config::get('constants.soap_api_username'),
                "pass_word" => Config::get('constants.soap_api_password'),
                "soap_header" => 'ZSD_HOS_SO_CREATE',
                "parameters" =>$parameters
                );
            $data_string = json_encode($input_arr);
                //print_r($data_string);exit;
            $url="https://tfms.nupco.com/nupco_service/hos_so_api.php";
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
                print_r($res);
                exit;

                if(!empty($res)){
                    $api_type = $res->O_WH_STOCK->TYPE;
                    $api_message = $res->O_WH_STOCK->MESSAGE;

                    $sales_order_log_data = array(
                        'hss_master_no'=>$pending_order_val->hss_master_no,
                        'hss_order_id'=>$pending_order_val->hss_order_id,
                        'sap_id'=>$pending_order_val->sap_id,
                        'status'=>$api_type,
                        'message'=>$api_message
                   );

                   DB::table('sales_order_log')->insert($sales_order_log_data);
                   echo $pending_order_val->hss_order_id.' Sales Order Added';
                }else{
                    echo $pending_order_val->hss_order_id. 'Something was wrong';
                }
        }
    }

   
    
}
