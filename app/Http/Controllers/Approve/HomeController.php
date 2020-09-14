<?php

namespace App\Http\Controllers\Approve;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class HomeController extends Controller
{

    public function index(){
        echo 'approve home';
    }
}
