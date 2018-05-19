<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Mail;
use Auth;
use Crypt;
use Responsive\Transaction;
use Responsive\User;
use URL;

class NewsLettersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
	public function post_newsletters_subscription(Request $request) {
		
		$id = @User::where('email',$request->email)->first(['id'])->id;
		 
		if($id == '' || $id == null)
		{
		 $flight = new \Responsive\NewsLetters;
         $flight->email = $request->email;
		 $flight->user_id = '';
         $flight->save();
		}
		else
			{
		 $flight = new \Responsive\NewsLetters;
         $flight->email = $request->email;
		 $flight->user_id = $id;
         $flight->save();
		}
		
	   return back();
	}

 
}
