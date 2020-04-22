<?php
/**
* Order Controller (1.0.0) 
*
* Handles all the order related methods
*/
namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session, DB, PDF;
use App\Models\Order;
use App\Models\StoreMaster;

class OrderController extends Controller
{

	/**
	* Checkout 
	* 
	* @param 
	*/

    public function checkout(){
    	try{
    		if(Session::has('cartItem')){
    			return view('front.checkout');
	    	}
	    	return redirect('store_list');
    	}
    	catch(Exception $e){
    		return redirect('store_list');
    	}
    }

    /**
	* User detail page 
	* 
	* @param 
	*/
    public function checkout_user(){
      	return view('front.userinfo');
    }


    /**
	* Get User detail 
	* 
	* @param Request $request
	*/
    public function save_user_detail(Request $request){
    	try{
    		if(Session::has('cartItem')){
    			if($request->name){

    				Session::put('userinfo', $request->all());    			
    			}
    			return view('front.order_summary');
	    	}
	    	Session::flash('error', 'Store selection is required');
	    	return redirect('store_list');
    	}
    	catch(Exception $e){
    		return redirect('store_list');
    	}
    	
    	
    }

    /**
	* Save order data
	* 
	* @param Request $request
	*/
    public function save_order(Request $request){
    	
    	try{
	    	if((!Session::has('store_id')) || (!Session::has('cartItem')) || (!Session::has('orderType')) || (!Session::has('category_id'))){
	    		Session::flash('error', 'Store selection is required');
	    		return redirect('store_list');
	    	}
	    	if(!Session::has('userinfo')){
	    		Session::flash('error', 'User information is required');
	    		return redirect('checkout/user');
	    	}

	    	$store_email = StoreMaster::where('id', Session::get('store_id'))->first();
	    	date_default_timezone_set('Asia/Kolkata');
	    	$order = array(
	    			'store_id' => Session::get('store_id'),
	    			'order_type' => Session::get('orderType'),
	    			'category_id' => Session::get('category_id'),
	    			'cart_item' => json_encode(Session::get('cartItem')),
	    			'extra_item' => json_encode(Session::get('cartextra')),
	    			'name' => Session::get('userinfo')['name'],
	    			'email' => Session::get('userinfo')['email'],
	    			'mobile_no' => Session::get('userinfo')['mobile_no'],
	    			'address' => Session::get('userinfo')['address'],
	    			'city' => Session::get('userinfo')['city'],
	    			'state' => Session::get('userinfo')['state'],
	    			'status' => 1,  
	    			'subtotal' => $request['subtotal'],	
	    			'total' => $request->total, 		
	    			'delivery_ins' => $request->delivery_ins, 		
	    			'delivery_charge' => Session::get('delCharge'), 		
	    			'payment_method' => $request->pay_method,    			
	    			'zipcode' => Session::get('userinfo')['zipcode'],
	    			'additional_notes' => Session::get('userinfo')['additional_notes'],
	    			'created_at' => date('Y-m-d H:i:s')
				);
	    	DB::beginTransaction();
			$orderdata = Order::create($order);
			DB::commit();
	    	$pdf = PDF::loadView('front.invoice.order_invoice', compact('orderdata'));
			$data = [];
            $data['request'] = 'order_email';
            $data['name'] = $orderdata->name;
            $data['email'] = $orderdata->email;
            $data['store_email'] = $store_email->email;
            $data['order_detail'] = $orderdata;
            $data['attachment'] = $pdf;
            $data['pdf_name'] = str_pad($orderdata->id,6,"0", STR_PAD_LEFT).'.pdf';
            $data['store'] = $store_email;
            $strC = strlen($orderdata->id);
            $data['subject'] ="Thank you for placing the order!";// "Order Number: #". str_pad($orderdata->id,6,"0", STR_PAD_LEFT);
            $mail = sendMail($data);  
            if($mail==1){
				Session::flush();
				Session::flash('orderSuccessMsg', 'Order placed successfully, Check your mail to get order detail');
		    	return redirect('/');

            }else{
            	DB::rollback();
		    	Session::flash('error', 'Order Couldn\'t be prosessed');
		    	return redirect('/save_user_detail');
            }

    	}catch(Exception $e){
    		DB::rollback();
    		Session::flash('error', 'Something went wrong!');
    		return redirect('/save_user_detail');
    	}
    }

    

}
