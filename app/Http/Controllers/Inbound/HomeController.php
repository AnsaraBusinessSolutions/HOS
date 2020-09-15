<?php

namespace App\Http\Controllers\Inbound;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('inbound.home');
    }

    public function requestOrderDetail($order_code){
        return view('inbound.request_order_details');
    }
}
