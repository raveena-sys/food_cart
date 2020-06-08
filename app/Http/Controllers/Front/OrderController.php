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
use App\Models\StorePostalCode;
use App\Models\DiscountCoupon;

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
	    	/*if(!Session::has('userinfo')){
	    		Session::flash('error', 'User information is required');
	    		return redirect('checkout/user');
	    	}*/

	    	$store_email = StoreMaster::where('id', Session::get('store_id'))->first();
	    	date_default_timezone_set('MST');
	    	$discount = '0.00';
	    	if(Session::has('discount') && Session::has('coupon_type')){
	    		$coupon_type = Session::get('coupon_type');
	    		if($coupon_type == 'fixed_discount'){
	              $discount = '$'.number_format(Session::get('discount'),2);
	            }else{
	              
	              $discount = Session::get('discount').'%';

	            }
	    	}
	    	
	    	$order = array(
	    			'store_id' => Session::get('store_id'),
	    			'order_type' => Session::get('orderType'),
	    			'category_id' => Session::get('category_id'),
	    			'cart_item' => json_encode(Session::get('cartItem')),
	    			'extra_item' => json_encode(Session::get('cartextra')),
	    			'discount' => $discount,
	    			'gst' => $request->gst_price,
	    			'name' => $request->name,
	    			'email' => $request->email,
	    			'mobile_no' => $request->mobile_no,
	    			'address' => $request->address,
	    			'city' => $request->city,
	    			'state' => $request->state,
	    			'status' => 1,  
	    			'subtotal' =>$request['subtotal'],	
	    			'total' => $request->total, 		
	    			//'delivery_ins' => $request->delivery_ins, 		
	    			'delivery_charge' => Session::has('deliveryCharge')?Session::get('deliveryCharge'):0, 		
	    			'payment_method' => $request->pay_method,    			
	    			'zipcode' => $request->zipcode,
	    			'additional_notes' => $request->additional_notes,
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


    public function checkzipcode(Request $request){
    	if(Session::has('orderType') && Session::get('orderType')=='delivery'){
	    	if(!empty($request->zipcode)){
	    		$zipcode =  StorePostalCode::whereRaw("find_in_set('".$request->zipcode."',postal_code) <> 0" )->first();
	    		if(!empty($zipcode)){
	    			Session::put('deliveryCharge', $zipcode->price);
	    			$html = view('front.ajax.order_summary')->render();
	    			return response()->json(['status' => 'true', 'message' => 'Delivery charges applied', 'html' => $html]);
	    		}else{
	    			Session::forget('deliveryCharge');
	    			$html = view('front.ajax.order_summary')->render();
	    			return response()->json(['status' => 'false', 'message' => 'Store does not provide delivery at this area.', 'html' => $html]);
	    		}
	    	}
	    }else {
	    	$html = view('front.ajax.order_summary')->render();
	    	return response()->json(['status' => 'true', 'message' => '', 'html' => $html]);
	    }
    }

    public function checkcoupon(Request $request){
    	if(!empty($request->coupon)){
    		$coupon =  DiscountCoupon::where(['coupon_code' => $request->coupon, 'store_id' =>Session::get('store_id')])->first();

	    	if(!empty($coupon)){
	    		if($coupon->expired_at.' 23:59:59' >= date('Y-m-d H:i:s')){
	    			Session::put('discount',$coupon->coupon_amount );
	    			Session::put('coupon_type',$coupon->coupon_type );
	    			Session::put('coupon_code',$coupon->coupon_code );
		    		$html = view('front.ajax.checkout_item')->render();
		    		return response()->json(['status' => 200, 'message'=> 'Coupon added successfully', 'html' => $html]);	
	    		}else{
	    			Session::forget('discount' );
	    			Session::forget('coupon_type');
	    			Session::forget('coupon_code');
	    			$html = view('front.ajax.checkout_item')->render();
	    			return response()->json(['status' => 201, 'message'=> 'Coupon '.$request->coupon.' has been expired!', 'html' => $html]);
	    		}
	    	}else{
	    		return response()->json(['status' => 201, 'message'=> 'Coupon '.$request->coupon.' does not exist!']);
	    	}
    	}else {
	    	return response()->json(['status' => 201, 'message'=> 'Please enter Coupon!']);
	    }
    }	

}
