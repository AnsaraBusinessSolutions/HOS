<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect('store/home');
    }

    public function AddStock(Request $request){
        $f11 = $request->input('wsdl_link');
        $f12 = $request->input('user_name');
        $f13 = $request->input('pass_word');
        $f14 = $request->input('soap_header');
        $f15 = $request->input('parameters');
        
        $data = array(
        "wsdl_link" => $f11,
        "user_name" => $f12,
        "pass_word" => $f13,
        "soap_header" => $f14,
        "parameters" =>$f15
        );

        $data_string = json_encode($data);

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
        print_r($res);
    }
}
