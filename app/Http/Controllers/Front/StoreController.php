<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Models\StoreMaster;
use App\Models\PizzaSauceMaster;
use App\Models\PizzaCrustMaster;
use App\Models\PizzaSizeMaster;
use App\Models\SubCategory;
use App\Models\ToppingDips;
use App\Models\ToppingPizza;
use App\Models\Product;
use App\Models\Cms;
use App\Models\PizzaExtraCheese;
use App\Models\ToppingDonairShawarmaMediterranean;
use App\Models\ToppingWingFlavour;
use App\Repositories\StoreMasterRepository;
use File, Session;
use View;
use Response;

class StoreController extends Controller
{
    private $StoreMasterRepository;

    public function __construct(StoreMasterRepository $StoreMasterRepository)
    {
        $this->StoreMasterRepository = $StoreMasterRepository;
    }

    
    /**
   * Get item cart updated
   */
  public function productCustomiseDetails(Request $request){
  
    //echo '<pre>'; print_r($request->all()); echo '</pre>';die;
    if($request->product_id){
      return $this->productDetails($request, $request->product_id);
    }
     
  }




    /**
    *Cart functionality
    */

  public function productDetails(Request $request, $id){
      
      $req = $request->all();      
      $seg = $request->input('seg');
      $c_prod = $request->input('c_prod');
      //Subcategory ids
      $subcategory_ids = SubCategory::getConcatSubcategory(Session::get('category_id'));

      $query = Product::select('product.*', 'spp.store_id', 'spp.product_id', 'spp.custom_price');

      $query1 = clone $query;

      //all products
      $products = $query->whereIN('sub_category_id', $subcategory_ids)->Join('store_product_price as spp', function($join){
         $join->on('product.id', '=', 'spp.product_id');
         $join->where('spp.store_id', '=', Session::get('store_id'));
      })->where('product.status', 'active')->get();
    
      //selected product
      $product = $query1->Join('store_product_price as spp', function($join){
         $join->on('product.id', '=', 'spp.product_id');
         //$join->where('product.id', '=', 'spp.store_id');
      })->where('product.id', $id)->where('product.status', 'active')->first();

      $cartArr = Session::has('cartItem')?Session::get('cartItem'):[];
      $extra = array();
      $cart = array();
      $addPrice = isset($product->custom_price)?$product->custom_price:$product->price;
      if(isset($product)){
        //prepare customise items
        if(isset($req['custom'])){
          $price = 0;
          $tm_price = 0;
          $cart['custom'] = $req['custom'];
          if($product->topping_from == 'topping_pizza' || $product->topping_from == 'topping_dips')
          {
            if(isset($req['extra_cheese'])){

              $cart['extra_cheese'] = PizzaExtraCheese::where('id', $req['extra_cheese'])->first();
              $cart['extra_cheese_name'] = $cart['extra_cheese']->price; 
              if($cart['extra_cheese']->price){   
                $addPrice = (float)$addPrice+(float)$cart['extra_cheese']->price; 

              }

            }

            if(isset($req['size_master'])){

              $pizzaSizeName = PizzaSizeMaster::select('pizza_size_master.name', 'store_pizza_size.custom_price as price')->where('pizza_size_master.status', 'active')->join('store_pizza_size',
                function($join){
                  $join->on('store_pizza_size.size_id', '=', 'pizza_size_master.id');
                  $join->where('store_pizza_size.store_id', '=', Session::get('store_id'));
              })->where('pizza_size_master.id',  $req['size_master'])->first();

              $cart['size_master'] = $req['size_master'];
              $cart['size_master_price'] = $pizzaSizeName['price'];
              $cart['size_master_name'] = $pizzaSizeName['name'];

              if($pizzaSizeName['price']){              
                $addPrice = (float)$addPrice+(float)$pizzaSizeName['price'];
                
              }
            }

            if(isset($req['crust_master'])){

              $pizzaCrustName = PizzaCrustMaster::select('pizza_crust_master.name', 'store_pizza_crust.custom_price as price')->where('pizza_crust_master.status', 'active')
                ->join('store_pizza_crust',
                  function($join){
                    $join->on('store_pizza_crust.crust_id', '=', 'pizza_crust_master.id');
                    $join->where('store_pizza_crust.store_id', '=', Session::get('store_id'));
                })->where('pizza_crust_master.id',  $req['crust_master'])->first();

              $cart['crust_master'] = $req['crust_master'];
              $cart['crust_master_price'] = $pizzaCrustName['price'];
              $cart['crust_master_name'] = $pizzaCrustName['name'];
              if($pizzaCrustName['price']){       
                $addPrice = (float)$addPrice+(float)$pizzaCrustName['price'];
              }
              
            }

            if(isset($req['sauce_master'])){

              $pizzaSauceName = PizzaSauceMaster::select('pizza_sauce_master.name', 'store_pizza_sauce.custom_price as price')->where('pizza_sauce_master.status', 'active')->join('store_pizza_sauce',
                  function($join){
                    $join->on('store_pizza_sauce.sauce_id', '=', 'pizza_sauce_master.id');
                    $join->where('store_pizza_sauce.store_id', '=', Session::get('store_id'));
                })->where('pizza_sauce_master.id',  $req['sauce_master'])->first();


              $cart['sauce_master'] = $req['sauce_master'];
              $cart['sauce_master_name'] = $pizzaSauceName['name'];
              $cart['sauce_master_price'] = $pizzaSauceName['price'];
              if($pizzaSauceName['price']){       
                $addPrice = (float)$addPrice+(float)$pizzaSauceName['price'];
              }
            }
            $td_names = array();

            if(isset($req['dip_master']) && !empty($req['dip_master'])){
              foreach($req['dip_master'] as $k => $v){
                $td_name = ToppingDips::select('topping_dips.name', 'store_topping_dips.custom_price as price')->join('store_topping_dips',
                  function($join){
                    $join->on('store_topping_dips.top_dip_id', '=', 'topping_dips.id');
                    $join->where('store_topping_dips.store_id', '=', Session::get('store_id'));
                })->where('topping_dips.id',  $k)->first(); 



                if($v>0){
                  $price = $price+($td_name->price*$v);
                  $cart['dip_master_price'] = $price;
                  $cart['dip_master_name'][] = $td_name->name.' ('.$v.'), ($'.$price.')';    
                }   
              }
              if($price){
                $addPrice += (float)$price;
              }
              
            }

            
            if(isset($req['topping_master'])){
              foreach($req['topping_master'] as $k => $v){
                $td_name = ToppingPizza::select('topping_pizza.name', 'store_topping_pizza.custom_price as price')->join('store_topping_pizza',
                  function($join){
                    $join->on('store_topping_pizza.top_pizza_id', '=', 'topping_pizza.id');
                    $join->where('store_topping_pizza.store_id', '=', Session::get('store_id'));
                })->where('topping_pizza.id',  $k)->first(); 

                $tm_price = $tm_price+$td_name->price;
                $cart['topping_master_price'] = $tm_price;
                $cart['topping_master'] = $k;
                $cart['topping_master_name'][] = $td_name->name/*.' ('.$v.'),'*/;   
                $tm_name[] =$td_name;    
              } 
              if($tm_price){
                $addPrice += (float)$tm_price;  
              }    
            }

            
          }
          $tdsm_price = 0;
          if($product->topping_from == 'topping_donair_shawarma_mediterranean')
          {
            
            foreach($req['topping_master'] as $k1 => $v1){
              $td_name = ToppingDonairShawarmaMediterranean::select('topping_donair_shawarma_mediterranean.name', 'store_topping_donair.custom_price as price')->where('topping_donair_shawarma_mediterranean.status', 'active')->join('store_topping_donair',
            function($join){
              $join->on('store_topping_donair.top_donair_id', '=', 'topping_donair_shawarma_mediterranean.id');
              $join->where('store_topping_donair.store_id', '=', Session::get('store_id'));
          })->where('topping_donair_shawarma_mediterranean.id',  $k1)->first();    

              $tdsm_price = $tdsm_price+$td_name->price;
              $cart['topping_master_price'] = $tdsm_price;
              $cart['topping_master'] = $k1;
              $cart['topping_master_name'][] = $td_name->name/*.' ('.$v1.'),'*/;   
              $tm_name[] =$td_name;    
            } 
            if($tdsm_price){
              $addPrice += (float)$tdsm_price;
            }
          }
          $twf_price = 0;
          if($product->topping_from == 'topping_wing_flavour')
          {
            foreach($req['topping_master'] as $key => $val){
              $td_name = ToppingWingFlavour::select('topping_wing_flavour.name', 'store_topping_wing.custom_price as price')->where('topping_wing_flavour.status', 'active')->join('store_topping_wing',
            function($join){
              $join->on('store_topping_wing.top_wing_id', '=', 'topping_wing_flavour.id');
              $join->where('store_topping_wing.store_id', '=', Session::get('store_id'));
          })->where('topping_wing_flavour.id',  $key)->first();    

              $twf_price = $twf_price+$td_name->price;
              $cart['topping_master_price'] = $twf_price;
              $cart['topping_master'] = $key;
              $cart['topping_master_name'][] = $td_name->name/*.' ('.$val.'),'*/;   
              $tm_name[] =$td_name;    
            } 
            if($twf_price){
              $addPrice += (float)$twf_price;
            }
          }
          if(!empty($cart)){
            
            $extra = json_encode($cart);
          }
   
        }

        $addProduct = true;
        if(!empty($cartArr) && count($cartArr)>0){
          //Existing cart customisation

          foreach ($cartArr as $key => $value) {
            $price_ded = (isset($addPrice)?$addPrice:$req['product_custom_price'])/*/$value['quantity']*/;
            //product exist in cart

            if(($value['product_id'] == $product->id) &&  ($extra == $value['extra'])){

              $addProduct = false;
              if($req['sub'] == 0){     

                $cartArr[$key]['quantity'] = (int)$value['quantity']+1;
                $cartArr[$key]['price'] = $price_ded*$cartArr[$key]['quantity'];
                $msg = 'Item added successfully';
                 $addRemove = 2;
              }else{

                if($cartArr[$key]['quantity']>1){

                  $cartArr[$key]['quantity'] = (int)$value['quantity']-1;
                  $cartArr[$key]['price'] = $price_ded*$cartArr[$key]['quantity'];

                }else{
                  unset($cartArr[$key]);
                }    
                $msg = 'Item removed successfully';
                $addRemove = 1;
              }

            }
          }
          Session::put('cartItem', $cartArr);

        }
         //add product in cart
        if($addProduct){

          $priceadd = (isset($addPrice)?$addPrice:(isset($req['product_custom_price'])?$req['product_custom_price']:$req['product_price']));
          $cart['product_id'] = $product->id;
          $cart['price'] = $priceadd;
          $cart['quantity'] = 1;
          $cart['image'] = $product->thumb_image;
          $cart['name'] = $product->name;
          $cart['description'] = $product->description;
          /*if(!empty($extra) && count($extra)>0){
            $cart['extra'] = $extra;
          }else{
          }*/
          Session::put('cartextra', $extra);
          $cart['extra'] = $extra;
          if(!empty($cart) && count($cart)>0){
            $cartArr[] = $cart;
            Session::put('cartItem', $cartArr);
            $msg = 'Item added successfully';
             $addRemove = 2;
          }
        }
      }
      $subcategory = SubCategory::where(['category_id' => Session::get('category_id'), 'status' => 'active'])->orderby('id')->get();
      $html = view('front.ajax.cart_item', compact('products'))->render();
      $product_html = view('front.ajax.product_item', compact('products', 'subcategory'))->render();
      if($seg == 'checkout'){
        $html = view('front.ajax.checkout_item', compact('products'))->render();
      }

      return json_encode(array('status'=>true, 'msg' => $msg,  'html'=> $html, 'product_html' => $product_html, 'add' =>  $addRemove));
   }



  public function increamentCartQty(Request $request){
    try{

      $subcategory_ids = SubCategory::getConcatSubcategory(Session::get('category_id'));
      $seg = $request->input('seg');
      $query = Product::select('product.*', 'spp.store_id', 'spp.product_id', 'spp.custom_price');

      $query1 = clone $query;

      //all products
      $products = $query->whereIN('sub_category_id', $subcategory_ids)->leftJoin('store_product_price as spp', function($join){
         $join->on('product.id', '=', 'spp.product_id');
         $join->where('spp.store_id', '=', Session::get('store_id'));
      })->where('product.status', 'active')->get();
      $cartArr = Session::get('cartItem');
      $addProduct = true;
      $msg = '';
      if(!empty($cartArr) && count($cartArr)>0){
        //Existing cart customisation

        foreach ($cartArr as $key => $value) {
          $price_ded = (isset($value['price'])?$value['price']:0)/$value['quantity'];
          //product exist in cart
          if(($value['product_id'] == $request->id) &&  (abs(($request->price-$value['price'])/$value['price']) < 0.00001 )){

            if($request->sub == 0){     
              $cartArr[$key]['quantity'] = (int)$value['quantity']+1;
              $cartArr[$key]['price'] = $price_ded*$cartArr[$key]['quantity'];
              $msg = 'Item added successfully';
              $addRemove = 2;
            }else{

              if($cartArr[$key]['quantity']>1){

                $cartArr[$key]['quantity'] = (int)$value['quantity']-1;
                $cartArr[$key]['price'] = $price_ded*$cartArr[$key]['quantity'];
              }else{
                unset($cartArr[$key]);
              }    
              $msg = 'Item removed successfully';
              $addRemove = 1;
            }

            Session::put('cartItem', $cartArr);

          }
          else{
            $addRemove = 1;
          }
        }
      }

      $subcategory = SubCategory::where(['category_id' => Session::get('category_id'), 'status' => 'active'])->orderby('id')->get();
      $html = view('front.ajax.cart_item', compact('products'))->render();
      $product_html = view('front.ajax.product_item', compact('products', 'subcategory'))->render();
      if($seg == 'checkout'){
        $html = view('front.ajax.checkout_item', compact('products'))->render();
      }
      return json_encode(array('status'=>true, 'msg' => $msg,  'html'=> $html, 'product_html' => $product_html, 'add' =>  $addRemove));
    }catch(Exception $e){
      return json_encode(array('status'=>false, 'msg' => 'Somehthing went wrong'));
    }
  }


    /**
    *Get product list of selected menu 
    */
   public function getStoreMasterList($id){

        try {
            Session::put('category_id', $id);
            $subcategory_ids = SubCategory::getConcatSubcategory($id);

            $subcategory = SubCategory::where(['category_id' => $id, 'status' => 'active'])->orderby('id')->get();
            DB::enableQueryLog();
            $products = Product::select('product.*', 'spp.store_id', 'spp.product_id', 'spp.custom_price')->whereIN('sub_category_id', $subcategory_ids)->join('store_product_price as spp', function($join){
               $join->on('product.id', '=', 'spp.product_id');
               $join->where('spp.store_id', '=', Session::get('store_id'));
              })->where('product.status', 'active')->orderby('sub_category_id')->get();
            $cms = Cms::where(['page_slug'=>'menu_products'])->first();
            return view('front.menu_product', compact('products','subcategory', 'subcategory_ids', 'cms')) ;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }


    public function storemenu($category_id, $store_id)
    {
        try {
            $categoryId = $category_id;

            if (!empty($category_id)) {
                $categoryId = $category_id;
            } else {
                $categoryId = Session::get('category_id');
            }
            $sub_category = SubCategory::where(['status' => "active"])->where('category_id', '=', $categoryId)->get()->toArray();

            if (\is_array($sub_category)) {
                $subCat = $sub_category[0]['id'];
                // $subCat = 3;
                $list =  Product::where(['sub_category_id' => $subCat, 'status'=>'active'])->select(
                    'id',
                    'name',
                    'description',
                    'status',
                    'price',
                    'image',
                    'food_type',
                    'thumb_image'
                )->get()->toArray();
            }

            $html = View::make('product.listing', ['listing_details' => $list])->render();

            return view('storemenu', [
                'sub_category' =>   $sub_category,
                'first_sub_category' =>   $subCat,
                'html' => $html,
                'total' => count($list)
            ]);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function getProductBySubCategory($subCat)
    {
        try {

            // $subCat = $request->subCat;
            $subCat = 3;
            $list =  Product::where(['sub_category_id' => $subCat])->select(
                'id',
                'name',
                'description',
                'status',
                'price',
                'image',
                'food_type',
                'thumb_image'
            )->get()->toArray();

            $html = View::make('product.listing', ['listing_details' => $list])->render();

            return Response::json(['success' => true, 'html' => $html, 'total' => count($list)]);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function get_customise($id)
    {   
      if(!empty($id)){
        $product = Product::select('product.*', 'store_product_price.custom_price')->where(['product.id'=> $id, 'product.status'=> 'active'])->join('store_product_price', function($join){
          $join->on('store_product_price.product_id', '=', 'product.id');
          $join->where('store_product_price.store_id', '=', Session::get('store_id'));
        })->first();
        if($product->topping_from == 'topping_pizza' || $product->topping_from == 'topping_dips'){
          $sauce_master = PizzaSauceMaster::select('pizza_sauce_master.*', 'store_pizza_sauce.custom_price')->where('pizza_sauce_master.status', 'active')->join('store_pizza_sauce',
            function($join){
              $join->on('store_pizza_sauce.sauce_id', '=', 'pizza_sauce_master.id');
              $join->where('store_pizza_sauce.store_id', '=', Session::get('store_id'));
          })->get();

          $crust_master = PizzaCrustMaster::select('pizza_crust_master.*', 'store_pizza_crust.custom_price')->where('pizza_crust_master.status', 'active')
          ->join('store_pizza_crust',
            function($join){
              $join->on('store_pizza_crust.crust_id', '=', 'pizza_crust_master.id');
              $join->where('store_pizza_crust.store_id', '=', Session::get('store_id'));
          })->get();

          $size_master = PizzaSizeMaster::select('pizza_size_master.*', 'store_pizza_size.custom_price')->where('pizza_size_master.status', 'active')->join('store_pizza_size',
            function($join){
              $join->on('store_pizza_size.size_id', '=', 'pizza_size_master.id');
              $join->where('store_pizza_size.store_id', '=', Session::get('store_id'));
          })->get();

          $dip_master = ToppingDips::select('topping_dips.*', 'store_topping_dips.custom_price')->where('topping_dips.status', 'active')->join('store_topping_dips',
            function($join){
              $join->on('store_topping_dips.top_dip_id', '=', 'topping_dips.id');
              $join->where('store_topping_dips.store_id', '=', Session::get('store_id'));
          })->get();

          $topping_master = ToppingPizza::select('topping_pizza.*', 'store_topping_pizza.custom_price')->where('topping_pizza.status', 'active')->join('store_topping_pizza',
            function($join){
              $join->on('store_topping_pizza.top_pizza_id', '=', 'topping_pizza.id');
              $join->where('store_topping_pizza.store_id', '=', Session::get('store_id'));
          })->get();

          $extra_cheese = PizzaExtraCheese::select('pizza_extra_cheese.*', 'store_pizza_cheese.custom_price')->where('pizza_extra_cheese.status', 'active')->join('store_pizza_cheese',
            function($join){
              $join->on('store_pizza_cheese.cheese_id', '=', 'pizza_extra_cheese.id');
              $join->where('store_pizza_cheese.store_id', '=', Session::get('store_id'));
          })->get();

          $compact = array('sauce_master', 'crust_master', 'size_master', 'dip_master', 'product', 'topping_master', 'extra_cheese');
        }
        if($product->topping_from == 'topping_donair_shawarma_mediterranean'){
          $topping_master = ToppingDonairShawarmaMediterranean::select('topping_donair_shawarma_mediterranean.*', 'store_topping_donair.custom_price')->where('topping_donair_shawarma_mediterranean.status', 'active')->join('store_topping_donair',
            function($join){
              $join->on('store_topping_donair.top_donair_id', '=', 'topping_donair_shawarma_mediterranean.id');
              $join->where('store_topping_donair.store_id', '=', Session::get('store_id'));
          })->get();
          $compact = array('topping_master');
        }
        if($product->topping_from == 'topping_wing_flavour'){
          $topping_master = ToppingWingFlavour::select('topping_wing_flavour.*', 'store_topping_wing.custom_price')->where('topping_wing_flavour.status', 'active')->join('store_topping_wing',
            function($join){
              $join->on('store_topping_wing.top_wing_id', '=', 'topping_wing_flavour.id');
              $join->where('store_topping_wing.store_id', '=', Session::get('store_id'));
          })->get();
          $compact = array('topping_master');

        }
        $compact['product'] = 'product';
        //$html_topping = view('front.ajax.extra_topping', compact('topping_master'))->render();
        $html = view('front.ajax.customise_popup', compact($compact))->render();
        return json_encode(array('status' => 1, 'html' => $html/*, 'html_topping' => $html_topping*/));
      }else{
        return json_encode(array('status' => 0, 'msg' => 'Something went wrong'));
      }
    }
    
}
