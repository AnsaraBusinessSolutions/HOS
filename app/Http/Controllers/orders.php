<?php

namespace App\Http\Controllers;

use App\Model\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Session;
use Auth;
class orders extends Controller
{
	function __construct(OrderRepository $OrderRepository)
    {
        $this->order=$OrderRepository;
    }

	public function item(Request $request){
		$sno=$request->item;
		$items = $this->order->getdetails($sno);
		return response()->json([
            'a'  => $items[0]->nupco_trade_code,
            'b'  => $items[0]->customer_gen_code,
            'c'  => $items[0]->material_description,
            'd'  => $items[0]->UOM
            ]);
	}

    
}
