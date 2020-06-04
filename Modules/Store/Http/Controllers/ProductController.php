<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddProductRequest;
use Modules\Admin\Http\Requests\EditProductRequest;
use App\Repositories\ProductRepository;
use App\Repositories\PizzaSauceRepository;
use App\Repositories\PizzaSizeRepository;
use App\Repositories\PizzaCrustRepository;
use App\Repositories\ToppingWingFlavourRepository;
use App\Repositories\ToppingDonairShawarmaMediterraneanRepository;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use File;
use DB;

use View;

class ProductController extends Controller
{
    private $ProductRepository;
    private $PizzaSauce;
    private $PizzaCrust;
    private $PizzaSize;
    private $ToppingWing;
    private $ToppingDonair;

    public function __construct(ProductRepository $ProductRepository, PizzaSauceRepository $PizzaSauce, PizzaCrustRepository $PizzaCrust , PizzaSizeRepository $PizzaSize, ToppingWingFlavourRepository $ToppingWing,  ToppingDonairShawarmaMediterraneanRepository $ToppingDonair)
    {
        $this->ProductRepository = $ProductRepository;
        $this->PizzaSauce = $PizzaSauce;
        $this->PizzaCrust = $PizzaCrust;
        $this->PizzaSize = $PizzaSize;
        $this->ToppingWing = $ToppingWing;
        $this->ToppingDonair = $ToppingDonair;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('store::product.index');
    }
    public function getProductList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->ProductRepository->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }
    public function addProduct(Request $request)
    {
        $pizzaSauce = $this->PizzaSauce->getList($request);
        $data['sauce']= $pizzaSauce['data']->getData()->data;

        $pizzaCrust = $this->PizzaCrust->getList($request);
        $data['crust']= $pizzaCrust['data']->getData()->data;

        $pizzaSize = $this->PizzaSize->getList($request);
        $data['size']= $pizzaSize['data']->getData()->data;

        $toppingWing = $this->ToppingWing->getList($request);
        $data['toppingWing']= $toppingWing['data']->getData()->data;
        
        $toppingDonair = $this->ToppingDonair->getList($request);
        $data['toppingDonair']= $toppingDonair['data']->getData()->data;        
        $adminDetail = Auth::user();
        return view('store::product.add', ['adminDetail' => $adminDetail, 'topping'=> $data]);
    }

    public function getEditProductDetails(Request $request, $id)
    {
        try {
             $pizzaSauce = $this->PizzaSauce->getList($request);
            $data1['sauce']= $pizzaSauce['data']->getData()->data;

            $pizzaCrust = $this->PizzaCrust->getList($request);
            $data1['crust']= $pizzaCrust['data']->getData()->data;

            $pizzaSize = $this->PizzaSize->getList($request);
            $data1['size']= $pizzaSize['data']->getData()->data;

            $toppingWing = $this->ToppingWing->getList($request);
            $data1['toppingWing']= $toppingWing['data']->getData()->data;
            
            $toppingDonair = $this->ToppingDonair->getList($request);
            $data1['toppingDonair']= $toppingDonair['data']->getData()->data;      
            $data = $this->ProductRepository->getDetail($id);
            // print_r($data);            die;
            return view('store::product.edit', ['detail' => $data , 'topping'=> $data1]);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function saveProduct(AddProductRequest $request)
    {
    
        try {
            $result = $this->ProductRepository->create($request);
            return $result;

        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function updateProduct(EditProductRequest $request)
    {

        try {
            return $data = $this->ProductRepository->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteProduct(Request $request)
    {
        try {
            return $this->ProductRepository->delete($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getProductDetails($id)
    {
        try {
            $data = $this->ProductRepository->getDetail($id);
            return View::make('store::product.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewProduct($id)
    {
        $user = Product::select('product.*','category.name as cat_name','sub_category.name  as subcat_name')->where('product.id', $id)->join('sub_category', 'product.sub_category_id', '=', 'sub_category.id')->join('category', 'sub_category.category_id', '=', 'category.id')->first();
        return view('store::product.view', ['category' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->ProductRepository->changeStatus($request);
    }


    /**
     * Get Ajax Request and restun Data
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubCategoryList($id)
    {
        $cities = DB::table("sub_category")
            ->where("category_id", $id)
            ->where("status", 'active')
            ->pluck("name", "id"); // pluck is used to get list of array of selected key
        return json_encode($cities);
    }


    public function getCustomProducts(Request $request){
        /*$products = DB::table("product")->select("product.id", "name", "price", "custom_price")->leftjoin('store_product_price as spp', function($join){
                    $join->on('product.id', '=', 'spp.product_id');
                    $join->where('spp.store_id', '=', Auth::user()->store_id);
                });*/

        $products = DB::table("product")->select("product.id", "name", "price")->where(['status' => 'active', 'store_id' => Null]);

        if($request->id){
            $idArray = explode(',', $request->id);
            $products->whereNotIN("product.id", $idArray);
        }
        if($request->product_id){
            $products->where("product.id", $request->product_id);
            $data['products'] = $products->get();
            $data['selected_ids'] = $request->product_id;
            return view('store::product.productselection')->with($data);
        }
        $data['products'] = $products->get();
        $data['selected_ids'] ='';
        return view('store::product.productselection')->with($data);
    }


    
    public function addselection(Request $request){

        try {
            $result = $this->ProductRepository->storeProductSelection($request);
            return $result;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


}
