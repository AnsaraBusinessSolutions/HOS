<?php

namespace App\Model;
use Illuminate\Support\Facades\DB;
use Session;
class OrderRepository
{

    public function getdetails($sno)
    {
		$details_data=DB::table('master')->where('id',$sno)->orderBy('id','ASC')->get();
		return $details_data;		
    }

  
}	
	