<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StoreMaster;
use App\Models\Category;
use App\Models\Cms;
use App\Models\SocialLink;
use App\Models\DiscountCoupon;
use Session;
use Response;

class HomeController extends Controller
{
    private $userRepository,$cms;
    public function __construct(Cms $cms)
    {
        $this->cms = $cms;
    }
    
    public function index()
    {
        try {
            $cms = $this->cms->where(['page_slug'=>'home_page'])->first();
            $links = SocialLink::whereNULL('store_id')->first();

            return view('front.home', compact('cms', 'links'));
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    /**
    * function to get stores list 
    *
    * @param 
    *
    * @return Store list view
    *
    */
    public function getstores()
    {
        try {

        	$data['details'] = StoreMaster::where('status', 'active')->paginate(6);
            $data['coupon'] = DiscountCoupon::where('status', 'active')->get();
            $data['links'] = SocialLink::whereNULL('store_id')->first();
            $data['cms'] = $this->cms->where(['page_slug'=>'store_list'])->first();
            return view('front.storelist')->with($data);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    /**
    * function to get stores menu (Category List) 
    *
    * @param $orderType, $price, $head
    *
    * @return Store menu list view
    *
    */
    public function mainMenu($orderType, $price, $head='')
    {
        try {
            if(Session::has('store_id')){
            
                if ($orderType == "pickup" || $orderType == "delivery") {
                    Session::put('orderType', $orderType);
                } else {
                    Session::put('orderType', 'delivery');
                }
                $category = Category::select('category.*')->join('store_category',function($join){
                    $join->on('category.id', '=', 'store_category.cat_id');
                    $join->where('store_category.store_id', '=', Session::get('store_id'));
                })->where('status', '=', 'active')/*->where('store_id', Session::get('store_id'))*/->get();
                $cms = $this->cms->where(['page_slug'=>'menu_list'])->first();
                $links = SocialLink::where('store_id', Session::get('store_id'))->first();
                if($head){
                    return back();
                }
                return view('front.main_menu', compact('category', 'cms', 'links'));
            }else{
                return redirect(url('store_list'));
            }
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    /**
    * function to get Order Type page 
    *
    * @param $request 
    *
    * @return Order Type view
    *
    */
    public function order_type(Request $request)
    {
        try {   
                $storeID = Session::get('store_id'); 
                if($storeID != $request->id){
                    Session::forget('cartItem');
                }
                Session::put('store_id', $request->id); 
                if(Session::has('store_id')){   
                    $store = StoreMaster::where(['status'=> 'active', 'id'=> $request->id])->first();
                    Session::put('gst_per', $store->gst_per); 
                    $cms = $this->cms->where(['page_slug'=>'order_type'])->first();
                    $links = SocialLink::where('store_id', Session::get('store_id'))->first();
                    return view('front.order_type', compact('store', 'cms', 'links'));
                }else{
                    return redirect(url('store_list'));
                }
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
    * function to get Checkout page 
    *
    * @param $request 
    *
    * @return Checkout page
    *
    */
    public function checkout()
    {
        try {
            if(Session::has('store_id')){   
                $links = SocialLink::where('store_id', Session::get('store_id'))->first();
                return view('checkout', compact('links'));
            }else{
                return redirect(url('store_list'));
            }
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /*public function ordersummary()
    {
        try {
            return view('ordersummary');
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function cartpage()
    {
        try {
            return view('cartpage');
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function topping()
    {
        try {
            return view('topping_master');
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function aboutus()
    {
        try {
            return view('about_us');
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }*/
   


    /**
    * function to get AboutUs page 
    *
    * @param $request 
    *
    * @return AboutUs page
    *
    */
    public function aboutUs()
    {
        try{
             $cms = $this->cms->where(['page_slug'=>'about_us'])->first();
             $links = SocialLink::whereNULL('store_id')->first();
             return view('front.about_us', ['cms' => $cms, 'links' => $links]);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function integrated_digital_marketing()
    {
        try{
             $cms = $this->cms->where(['page_slug'=>'about_us'])->first();
             $links = SocialLink::whereNULL('store_id')->first();
             return view('front.integrated_digital_marketing', ['cms' => $cms, 'links' => $links]);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
     public function branded_food_ordering_platform()
    {
        try{
             $cms = $this->cms->where(['page_slug'=>'about_us'])->first();
             $links = SocialLink::whereNULL('store_id')->first();
             return view('front.branded_food_ordering_platform', ['cms' => $cms, 'links' => $links]);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    /*public function faq()
    {
        try
        {
             $cms = $this->cms->where(['page_name'=>'faq'])->first();
             return view('front.faq', ['cms' => $cms]);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function career()
    {
        try
        {
             $cms = $this->cms->where(['page_name'=>'career'])->first();
             return view('front.career', ['cms' => $cms]);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }*/
    public function privacyPolicy()
    {
        try
        {    $links = SocialLink::whereNULL('store_id')->first();
             $cms = $this->cms->where(['page_slug'=>'privacy_policy'])->first();
             return view('front.privacy_policy', ['cms' => $cms, 'links' => $links]);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    /*public function getData(Request $request)
    {

        $food_type_master_id = $request->input('food_type_master_id');
        $name = $request->input('name');
        $price = $request->input('price');
        $thumb_image = $request->input('thumb_image');
        $image = $request->input('image');
        $status = $request->input('status');


        $data = array('food_type_master_id' => $food_type_master_id, "name" => $name, "price" => $price, 'thumb_image' => $thumb_image, "image" => $image, "status" => $status, "created_by" => date('Y-m-d H:i:s'), "updated_by" => date('Y-m-d H:i:s'));
        DB::table('topping_master')->insert($data);
        // echo "Record inserted successfully.<br/>";
        // echo '<a href = "insert">Click Here</a> to go back.';

        $data = array('status' => "Success", "message" => 'Record inserted successfully.', "Link" => '<a href = "insert">Click Here</a> to go back.');
        return json_encode($data);
    }*/

}
