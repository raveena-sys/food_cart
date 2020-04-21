<?php

namespace App\Repositories;

use App\EmailQueue\CreateEmployee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use Auth;
use Datatables;
use App\Common\Helpers;
use App\Models\Job;
use App\Models\Product;
use App\Models\StoreProductPrice;
use \DB;
use File;
/**
 * Class ProductRepository.
 */

class ProductRepository
{
    private $product, $user;

    public function __construct(
        User $user,
        Product $product
    ) {
        $this->user = $user;
        $this->product = $product;
    }

    public function getList($request)
    {

        try {
            $query = \DB::table('product as prd')
                ->join('sub_category as sbcat', 'prd.sub_category_id', '=', 'sbcat.id')
                ->leftjoin('size_master as sizem', 'prd.size_master_id', '=', 'sizem.id');
                $query->join('category as cat', 'sbcat.category_id', '=', 'cat.id')->where('prd.status', '!=', 'deleted');
                //->join('store_master as sm', 'sm.id', '=', 'prd.store_id')
                
                if(Auth::user()->user_type == 'store'){
                    $query->join('store_product_price as spp', function($join){
                        $join->on('prd.id', '=', 'spp.product_id');
                        $join->where('spp.store_id', '=', Auth::user()->store_id);
                    })->select([
                    'prd.id', 'prd.name', 'prd.status', 'prd.description', 'prd.price', 'prd.quantity', 'prd.topping_from',
                    'prd.sub_category_id', 'prd.size_master_id', /*'prd.store_id',*/
                    'prd.food_type', 'sbcat.category_id', 'sbcat.name as sub_name',
                    'sizem.name as size_name'/*, 'sm.name as store_name'*/, 'cat.name as category_name', 'spp.custom_price', 'spp.status'
                    ]);
                }
                else{
                    $query->leftjoin('store_master as sm', 'sm.id', '=', 'prd.store_id');
                    $query->select([
                        'prd.id', 'prd.name', 'prd.status', 'prd.description', 'prd.price', 'prd.quantity', 'prd.topping_from',
                        'prd.sub_category_id', 'prd.size_master_id', /*'prd.store_id',*/
                        'prd.food_type', 'sbcat.category_id', 'sbcat.name as sub_name',
                        'sizem.name as size_name', 'sm.name as store_name', 'cat.name as category_name'/*, 'spp.custom_price'*/
                    ]);
                }
                
                $query->orderby('prd.id', 'desc');

            if (!empty($request->status)) {
                $query->where('status', $request->status);
            }

            if (!empty($request->category_id)) {
                $query->where('category_id', $request->category_id);
            }
            $product = $query->get();
            $tableResult = Datatables::of($product)->addIndexColumn()

                ->filter(function ($instance) use ($request) {

                    if (!empty($request->name)) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(Str::lower($row['name']), Str::lower(trim($request->name))) ? true : false;
                        });
                    }

                })
                ->editColumn('name', function ($data) {
                   
                    $userName = $data->name;
                  
                    return $userName;
                })
                ->editColumn('description', function ($data) {
                    return str_limit($data->description, 30);
                })
        
                ->editColumn('store_name', function ($data) {
                    return isset($data->store_name)?$data->store_name:'Admin';
                })

                ->editColumn('size_name', function ($data) {
                    return $data->size_name;
                })

                ->editColumn('sub_name', function ($data) {
                    return $data->sub_name;
                })

                ->editColumn('price', function ($data) {
                    if(Auth::user()->user_type == 'store')
                    {
                        return round($data->custom_price,2);
                    }else{
                        return round($data->price,2);
                    }
                })

                ->editColumn('quantity', function ($data) {
                    return $data->quantity;
                })

                ->editColumn('topping_from', function ($data) {
                    $result = $data->topping_from;

                    if ($data->topping_from == "topping_pizza") {
                        $result = "Pizza Toppings";
                    }
                    if ($data->topping_from == "topping_wing_flavour") {
                        $result = "Wing Flavour Toppings";
                    }
                    if ($data->topping_from == "topping_dips") {
                        $result = "Other Toppings";
                    }
                    if ($data->topping_from == "topping_donair_shawarma_mediterranean") {
                        $result = "Donair Shawarma Mediterranean";
                    }

                    if ($data->topping_from == "none") {
                        $result = "Not Required";
                    }


                    return $result;
                })
                ->editColumn('category_name', function ($data) {
                    return $data->category_name;
                })


                ->addColumn('status', function ($row) {
                    $show="onclick='changeStatus($row->id)'";
                    if(Auth::user()->user_type == 'store'){
                        $show = 'disabled';
                    }
                    $status = isset($row->status) ? $row->status : "";
                    $checked = ($status == 'active') ? "checked" : "";

                    $btn = "<div class='switch'> <label> <input class='onoffswitch-checkbox switchchange' name='onoffswitch' type='checkbox' data-status='$status' id='category$row->id' $checked  $show data-tableid='employee-lenderslist'> <span class='lever'></span> </label> </div>";
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    if(Auth::user()->user_type == 'store')
                    {
                        $viewUrl = url('store/product/view/' . $row->id);
                        $editURL = url('store/product/edit/' . $row->id);
                    }else{
                        $viewUrl = url('admin/product/view/' . $row->id);
                        $editURL = url('admin/product/edit/' . $row->id);
                    }

                    $btn = "";

                    $btn = '<div class="dropdown">
                           <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <span class="icon-keyboard_control"></span>
                           </a>
                           <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                               <a class="dropdown-item" href="' . $viewUrl . '"   >View</a>
                               <a class="dropdown-item" href="' . $editURL . '"   >Edit</a>';
                               /*if(Auth::user()->user_type=='store'){

                                $btn .= '<a href="javascript:void(0);" onclick="showEditProductPrice('.$row->id.')" class="dropdown-item">Edit Price</a>';
                               }*/
                                $btn .= '<a class="dropdown-item" href="javascript:void(0);"  id=remove' . $row->id . ' data-url=' . url('admin/employee-delete') . ' data-name=' . $row->name . ' data-tableid="data-listing" onclick="deleteCategory(' . $row->id . ')" >Delete</a>
                           </div>
                       </div>';
                    return $btn;
                })
                ->rawColumns(['status', 'action', 'name'])
                ->make(true);

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getDetail($id)
    {
        try {


            /*$product = \DB::table('product as prd')
                ->join('sub_category as sbcat', 'prd.sub_category_id', '=', 'sbcat.id')
                ->join('size_master as sizem', 'prd.size_master_id', '=', 'sizem.id')
                ->join('category', 'sbcat.category_id', '=', 'category.id')
                ->where('prd.id', '=', $id)
                ->where('prd.status', '!=', 'deleted')
                ->select([
                    'prd.id', 'prd.name', 'prd.status', 'prd.description', 'prd.image',
                    'prd.sub_category_id', 'prd.size_master_id',
                    'prd.store_id', 'prd.price', 'prd.quantity',
                    'prd.topping_from', 'prd.food_type', 'sbcat.category_id',
                    'sizem.name as size_name'
                ])->first();*/
            $query = $this->product->where('product.id', $id)->join('sub_category', 'product.sub_category_id', '=', 'sub_category.id')->join('category', 'sub_category.category_id', '=', 'category.id');
            if(Auth::user()->user_type == 'store'){
                $query->join('store_product_price', 'store_product_price.product_id', '=', 'product.id')->where('store_product_price.store_id', Auth::user()->store_id);
                $product = $query->select('product.*','category.id as cat_id' , 'store_product_price.custom_price as custom_price')->first();
            }
            else{
                $product = $query->select('product.*','category.id as cat_id')->first();

            }

            return  $product;

            // return $this->product->where('id', $id)->first();
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Delete user by Id
    */
    public static function delete($request)
    {
        try {

            $userData = Product::where(['id' => $request->id])->select('id', 'name', 'description', 'status', 'store_id')->first();
            if (!empty($userData)) {
                if(Auth::user()->user_type =='store'){
                    if(Auth::user()->store_id == $userData['store_id']){
                        $storeProductData = StoreProductPrice::where('store_id', Auth::user()->store_id)->where('product_id', $userData['id'] )->delete();
                        $userData->update(array('status' => 'deleted'));
                    }else{
                        $storeProductData = StoreProductPrice::where('store_id', Auth::user()->store_id)->where('product_id', $userData['id'])->delete();
                    }
                }else{
                    $userData->update(array('status' => 'deleted'));
                }
                $message = 'Product successfully deleted.';
                $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            } else {
                $response = ['success' => false, 'message' => 'User does not found.', 'error' => [], 'data' => []];
            }
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {

            $userData = Auth::user();
            $category = $this->product->where('id', $request->id);

            $fileName = "";
            $profilePath = public_path() . '/uploads/products';

            if (!is_dir($profilePath)) {
                File::makeDirectory($profilePath, $mode = 0777, true, true);
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $fileExtension = strtolower($file->getClientOriginalExtension());
                $name = time().'.'.$fileExtension;
                $imageExist = public_path() . '/uploads/products/' . $name;
                $request->file('image')->move($profilePath, $name);

                $post['thumb_image'] = $name;
                $post['image'] = $name;
            }

            $post['name'] = $request['name'];
            $post['sub_category_id'] = $request['sub_category_id'];
            $post['size_master_id'] = $request['size_master_id'];
            $post['food_type'] = $request['food_type'];

            $post1 =array();
            if(Auth::user()->user_type == 'admin'){
                $post['price'] = $request['price'];
                if($request['storeid']){
                    $post1['custom_price'] = $request['price'];

                    StoreProductPrice::where(['product_id'=>$request->id, 'store_id'=>$request['storeid']])->update($post1);
                }
                
            }else{
                if(isset($request['store_id']) && $request['store_id'] == Auth::user()->store_id){         
                    $post['price'] = $request['price'];                   
                }
                $post1['store_id'] = Auth::user()->store_id;
                $post1['product_id'] = $request->id;
                $post1['custom_price'] = $request['price'];
                if(!empty($post1)){
                    StoreProductPrice::updateOrCreate(['product_id'=>$post1['product_id'], 'store_id'=>$post1['store_id']], $post1);
                }
            }
            $post['quantity'] = $request['quantity'];
            $post['topping_from'] = $request['topping_from'];
            $post['description'] = $request['description'];
            $post['created_by']     = $userData->id;
            $post['updated_by']     = $userData->id;

            $category->update($post);
            
            DB::commit();
            $message = "Product successfully updated.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            // return $response;
            $segment = $request->segment(1);
            return redirect($segment.'/product')->with('success',  $message);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function create($request)
    {
        /*echo "<pre>"; print_r($request['name']); echo "</pre>";die;*/
        DB::beginTransaction();
        try {
            $userData = Auth::user();

            $fileName = "";
            $profilePath = public_path() . '/uploads/products';
            if (!is_dir($profilePath)) {
                File::makeDirectory($profilePath, $mode = 0777, true, true);
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $fileExtension = strtolower($file->getClientOriginalExtension());
                $name = time().'.'.$fileExtension;
                $imageExist = public_path() . '/uploads/products/' . $name;
                $request->file('image')->move($profilePath, $name);

                $post['thumb_image'] = $name;
                $post['image'] = $name;
            }

            $post['name'] = $request['name'];

            if(isset($request['store_id'])){
                $post['store_id'] = $request['store_id'];
            }

            $post['sub_category_id'] = $request['sub_category_id'];
            $post['size_master_id'] = $request['size_master_id'];
            $post['food_type'] = $request['food_type'];
            $post['price'] = $request['price'];
            $post['quantity'] = $request['quantity'];
            $post['topping_from'] = $request['topping_from'];
            $post['description'] = $request['description'];
            $post['created_by']     = $userData->id;
            $post['updated_by']     = $userData->id;

            $products = $this->product->create($post);

            if(isset($request['store_id'])){
                
                $post1['store_id'] = Auth::user()->store_id;
                $post1['product_id'] = $products->id;
                $post1['custom_price'] = $products->price;           
                StoreProductPrice::updateOrCreate(['product_id'=>$post1['product_id'], 'store_id'=>$post1['store_id']], $post1);
            }
            DB::commit();
            $message = "Product added sucsessfully.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            //return $response;
            $segment = $request->segment(1);
            
            return redirect($segment.'/product')->with('success',  $message);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Change user status by Id
    */
    public static function changeStatus($request)
    {
        try {
            $userData = Product::where(['id' => $request->id])->select('id', 'name', 'description', 'status')->first();
            if (!empty($userData)) {
                if(Auth::user()->user_type =='store'){
                    if(Auth::user()->store_id == $userData['store_id']){
                        $storeProductData = StoreProductPrice::where('store_id', Auth::user()->store_id)->where('product_id', $userData['id'] )->update(['status' =>$request->status]);
                        $userData->update(array('status' => $request->status));
                    }else{
                        $storeProductData = StoreProductPrice::where('store_id', Auth::user()->store_id)->where('product_id', $userData['id'])->update(['status' =>$request->status]);
                    }
                }else{
                    $userData->update(array('status' => $request->status));
                }
                $message = 'Status successfully changed.';
                $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            } else {
                $response = ['success' => false, 'message' => 'Store does not found.', 'error' => [], 'data' => []];
            }
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getManagers($id)
    {

        $data = "<option value=''>Select Manager</option>";
        // $managers = $this->manager->where('company_id', $id)->get();
        // foreach ($managers as $value) {
        //     $id = $value->id;
        //     $manager = $value->managerInfo->name;
        //     $data .= "<option value='$id'>$manager</option>";
        // }
        return $data;
    }

    public function storeProductSelection($request)
    {
        DB::beginTransaction();
        try {

            foreach($request->id as $v){

                $post['store_id'] = Auth::user()->store_id;
                $post['product_id'] = $v['id'];
                $post['custom_price'] = $v['price'];           
                StoreProductPrice::updateOrCreate(['product_id'=>$post['product_id'], 'store_id'=>$post['store_id']], $post);
            }
            DB::commit();
            $message = "Product added sucsessfully.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

}
