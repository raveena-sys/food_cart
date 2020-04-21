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
use App\Models\PizzaSizeMaster;
use App\Models\PizzaExtraCheese;
use App\Models\StorePizzaSize;
use App\Models\StorePizzaCheese;
use \DB;

/**
 * Class EmployeeRepository.
 */

class PizzaSizeRepository
{
    private $PizzaSize, $user, $PizzaExtraCheese;

    public function __construct(
        User $user,
        PizzaSizeMaster $PizzaSize,
        PizzaExtraCheese $PizzaExtraCheese
    ) {
        $this->user = $user;
        $this->PizzaSize = $PizzaSize;
        $this->PizzaExtraCheese = $PizzaExtraCheese;
    }

    public function getList($request)
    {
        try {
            $PizzaSize = \DB::table('pizza_size_master as pizsize')
                ->join('size_master as sizem', 'pizsize.size_master_id', '=', 'sizem.id')
                ->where('pizsize.status', '!=', 'deleted');
                if(Auth::user()->user_type =='store'){

                    $PizzaSize->select('pizsize.*', 'store_pizza_size.custom_price','store_pizza_size.status as s_status', 'sizem.name as size_name')->join('store_pizza_size', function($join){
                        $join->on('store_pizza_size.size_id', '=', 'pizsize.id');
                        $join->where('store_pizza_size.store_id', '=', Auth::user()->store_id);
                    });

                }else{
                    $PizzaSize->select([
                        'pizsize.id', 'pizsize.name','pizsize.price', 'pizsize.status', 'pizsize.description',
                        'sizem.name as size_name'
                    ]);
                }

                $PizzaSize->orderby('id', 'desc');
                

                if (!empty($request->status)) {
                    $PizzaSize =   $PizzaSize->where('pizsize.status', Str::lower(trim($request->status)));
                }

                if (!empty($request->category_id)) {
                    $PizzaSize = $PizzaSize->where('pizsize.size_master_id', $request->size_master_id);
                }
                $PizzaSize = $PizzaSize->get();
            //$PizzaSize = $this->PizzaSize->where('status', '!=', 'deleted');
            //->where(['user_type' => $request->type, 'user_role' => 'employee']);

            $tableResult = Datatables::of($PizzaSize)->addIndexColumn()

                ->filter(function ($instance) use ($request) {

                    if (!empty($request->name)) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(Str::lower($row['name']), Str::lower(trim($request->name))) ? true : false;
                        });
                    }


                    // if (!empty($request->company)) {
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //         return Str::contains(Str::lower($row['company']), Str::lower(trim($request->company))) ? true : false;
                    //     });
                    // }
                    // if (!empty($request->manager)) {
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //         return Str::contains(Str::lower($row['manager']), Str::lower(trim($request->manager))) ? true : false;
                    //     });
                    // }
                })
                ->editColumn('name', function ($data) {
                    // $imageUrl = getUserImage($data['profile_image'], 'uploads');
                    $userName = $data->name;
                    // $viewUrl = url('admin/manage-PizzaSize-view/' . $data->id);
                    // $name = '
                    // <div class="user-img">
                    //  <img src="' . $imageUrl . '" alt="user-img" class="img-fluid rounded-circle">
                    // </div>
                    //  <a  class="theme-color name">' . $userName . '</a>';
                    return $userName;
                    //href="' . $viewUrl . '"
                })
                ->editColumn('description', function ($data) {
                    return $data->description;
                })

                ->editColumn('size_name', function ($data) {
                    return $data->size_name;
                })
                ->editColumn('price', function ($data) {
                    if(Auth::user()->user_type == 'store')
                    {
                        return round($data->custom_price,2);
                    }else{
                        return round($data->price,2);
                    }
                })
                // ->editColumn('company', function ($category) {

                //     return (!empty($category->employeeDetails) ? ucfirst($category->employeeDetails->company->company_title) : '');
                // })
                // ->editColumn('manager', function ($category) {
                //     return (!empty($category->employeeDetails) ? ucfirst($category->employeeDetails->manager->managerInfo->name) : '');
                // })
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
                ->addColumn('action', function ($row) use($request) {
                    $segment = $request->segment(1);
                    $viewUrl = url($segment.'/manage-pizza-size/view/' . $row->id);

                    $btn = "";
                    $btn = '<div class="dropdown">
                           <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <span class="icon-keyboard_control"></span>
                           </a>
                           <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                               <a class="dropdown-item" href="' . $viewUrl . '"   >View</a>
                               <a class="dropdown-item" href="javascript:void(0);" onclick="ediCategory(' . $row->id . ')">Edit</a>
                               <a class="dropdown-item" href="javascript:void(0);"  id=remove' . $row->id . ' data-url=' . url('admin/employee-delete') . ' data-name=' . $row->name . ' data-tableid="data-listing" onclick="deleteCategory(' . $row->id . ')" >Delete</a>
                           </div>
                       </div>';
                    return $btn;
                })
                // <a class="dropdown-item" href="javascript:void(0);" onclick="'.$delete.'">Delete</a>
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
            $query =  $this->PizzaSize->where('pizza_size_master.id', $id);
            if(Auth::user()->user_type == 'store'){
                $query->join('store_pizza_size', 'store_pizza_size.size_id', '=', 'pizza_size_master.id')->where('store_pizza_size.store_id', Auth::user()->store_id);
                $size = $query->select('pizza_size_master.*', 'store_pizza_size.custom_price as custom_price')->first();
            }
            else{
                $size = $query->select('pizza_size_master.*')->first();
            }

            return $size; 
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

            $userData = PizzaSizeMaster::where(['id' => $request->id])->select('id', 'name', 'description', 'status', 'store_id')->first();
            if (!empty($userData)) {
                if(Auth::user()->user_type =='store'){
                    if(Auth::user()->store_id == $userData['store_id']){
                        $storeSizeData = StorePizzaSize::where('store_id', Auth::user()->store_id)->where('size_id', $userData['id'] )->delete();
                        $userData->update(array('status' => 'deleted'));
                    }else{
                        $storeSizeData = StorePizzaSize::where('store_id', Auth::user()->store_id)->where('size_id', $userData['id'])->delete();
                    }
                }else{
                    $userData->update(array('status' => 'deleted'));
                }
                $message = 'Pizza Size successfully deleted.';
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
        try {
            $category = $this->PizzaSize->where('id', $request->id);
            $userNewData = [
                'description' => $request['description'],
                'name' => $request['name'],
                //'price' => $request['price']
            ];
            $post1 =array();
            if(Auth::user()->user_type == 'admin'){
                $userNewData['price'] = $request['price'];
                if($request['storeid']){
                    $post1['custom_price'] = $request['price'];

                    StorePizzaSize::where(['size_id'=>$request->id, 'store_id'=>$request['storeid']])->update($post1);
                }
                
            }else{
                if(isset($request['store_id']) && $request['store_id'] == Auth::user()->store_id){         
                    $userNewData['price'] = $request['price'];                   
                }
                $post1['store_id'] = Auth::user()->store_id;
                $post1['size_id'] = $request->id;
                $post1['custom_price'] = $request['price'];
                if(!empty($post1)){
                    StorePizzaSize::updateOrCreate(['size_id'=>$post1['size_id'], 'store_id'=>$post1['store_id']], $post1);
                }
            }
            $category->update($userNewData);

            $message = "Pizza Size successfully updated.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $userData = Auth::user();
            $post['description'] = $request['description'];
            $post['name'] = $request['name'];
            $post['price'] = $request['price'];
            $post['size_master_id'] = $request['size_master_id'];

            $post['created_by']     = $userData->id;
            $post['updated_by']     = $userData->id;

            if(isset($request->store_id)){
                $post['store_id'] = $request['store_id'];
            }

            $size = $this->PizzaSize->create($post);
            if(isset($request->store_id)){
                $post1['store_id'] = $request['store_id'];
                $post1['size_id'] = $size->id;
                $post1['custom_price'] = $request['price'];

                StorePizzaSize::updateOrCreate(['size_id'=>$post1['size_id'], 'store_id'=>$post1['store_id']], $post1);
            }
            DB::commit();
            $message = "Pizza Size added sucsessfully.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
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
            $userData = PizzaSizeMaster::where(['id' => $request->id])->select('id', 'name', 'description', 'status','store_id')->first();
            if (!empty($userData)) {
                if(Auth::user()->user_type =='store'){
                    if(Auth::user()->store_id == $userData['store_id']){
                        $storeSizeData = StorePizzaSize::where('store_id', Auth::user()->store_id)->where('size_id', $userData['id'] )->update(['status' =>$request->status]);
                        $userData->update(array('status' => $request->status));
                    }else{
                        $storeSizeData = StorePizzaSize::where('store_id', Auth::user()->store_id)->where('size_id', $userData['id'])->update(['status' =>$request->status]);
                    }
                }else{
                    $userData->update(array('status' => $request->status));
                }
                $message = 'Status successfully changed.';
                $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            } else {
                $response = ['success' => false, 'message' => 'Pizza Sauce does not found.', 'error' => [], 'data' => []];
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



    /*Pizza cheese module*/

    public function getPizzaCheeseList($request)
    {
        try {
            $query = PizzaExtraCheese::where('pizza_extra_cheese.status', '!=', 'deleted');
            if(Auth::user()->user_type =='store'){

                $query->select('pizza_extra_cheese.*', 'store_pizza_cheese.custom_price')->join('store_pizza_cheese', function($join){
                        $join->on('store_pizza_cheese.cheese_id', '=', 'pizza_extra_cheese.id');
                        $join->where('store_pizza_cheese.store_id', '=', Auth::user()->store_id);
                    });

            }

            if (!empty($request->status)) {
                $query->where('pizza_extra_cheese.status', Str::lower(trim($request->status)));
            }
            $PizzaCheese = $query->orderby('pizza_extra_cheese.id', 'desc')->get();
            $tableResult = Datatables::of($PizzaCheese)->addIndexColumn()

                ->filter(function ($instance) use ($request) {

                    if (!empty($request->name)) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(Str::lower($row['name']), Str::lower(trim($request->name))) ? true : false;
                        });
                    }
                })
                ->editColumn('name', function ($data) {
                   
                    $userName = $data->pizzaSize->name;
                   
                    return $userName;
                })
                /*->editColumn('store', function ($data) {
                    return $data->store->name;
                })*/

                ->editColumn('price', function ($data) {
                    if(Auth::user()->user_type == 'store')
                    {
                        return round($data->custom_price,2);
                    }else{
                        return round($data->price,2);
                    }
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
                ->addColumn('action', function ($row) use($request) {
                    $segment = $request->segment(1);
                    $viewUrl = url($segment.'/manage-pizza-cheese/view/' . $row->id);

                    $btn = "";
                    $btn = '<div class="dropdown">
                           <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <span class="icon-keyboard_control"></span>
                           </a>
                           <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                               <a class="dropdown-item" href="' . $viewUrl . '"   >View</a>
                               <a class="dropdown-item" href="javascript:void(0);" onclick="editCheese(' . $row->id . ')">Edit</a>
                               <a class="dropdown-item" href="javascript:void(0);"  id=remove' . $row->id . ' data-url=' . $segment.'/manage-pizza-cheese/view/'.$row->id . ' data-name=' . $row->name . ' data-tableid="data-listing" onclick="deleteCheese(' . $row->id . ')" >Delete</a>
                           </div>
                       </div>';
                    return $btn;
                })
                // <a class="dropdown-item" href="javascript:void(0);" onclick="'.$delete.'">Delete</a>
                ->rawColumns(['status', 'action', 'name'])
                ->make(true);

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getPizzaCheeseDetail($id)
    {
        try {
            $query = $this->PizzaExtraCheese->where('pizza_extra_cheese.id', $id);
            if(Auth::user()->user_type == 'store'){
                $query->join('store_pizza_cheese', 'store_pizza_cheese.cheese_id', '=', 'pizza_extra_cheese.id')->where('store_pizza_cheese.store_id', Auth::user()->store_id);
                $cheese = $query->select('pizza_extra_cheese.*', 'store_pizza_cheese.custom_price as custom_price')->first();
            }
            else{
                $cheese = $query->select('pizza_extra_cheese.*')->first();
            }
            return $cheese;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Delete user by Id
    */
    public static function deletePizzaCheese($request)
    {
        try {

            $userData = PizzaExtraCheese::where(['id' => $request->id])->first();
            if (!empty($userData)) {
                if(Auth::user()->user_type =='store'){
                    if(Auth::user()->store_id == $userData['store_id']){
                        $storeCheeseData = StorePizzaCheese::where('store_id', Auth::user()->store_id)->where('cheese_id', $userData['id'] )->delete();
                        $userData->update(array('status' => 'deleted'));
                    }else{
                        $storeCheeseData = StorePizzaCheese::where('store_id', Auth::user()->store_id)->where('cheese_id', $userData['id'])->delete();
                    }
                }else{
                    $userData->update(array('status' => 'deleted'));
                }
                $message = 'Pizza Cheese successfully deleted.';
                $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            } else {
                $response = ['success' => false, 'message' => 'Useras does not found.', 'error' => [], 'data' => []];
            }
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function updatePizzaCheese($request)
    {
        try {
            $category = $this->PizzaEtraCheese->where('id', $request->id);
            $userNewData = [
                //'description' => $request['description'],
                'size_master_id' => $request['size_master_id'],
                //'price' => $request['price']
            ];

            $post1 =array();
            if(Auth::user()->user_type == 'admin'){
                $userNewData['price'] = $request['price'];
                if($request['storeid']){
                    $post1['custom_price'] = $request['price'];

                    StorePizzaCheese::where(['cheese_id'=>$request->id, 'store_id'=>$request['storeid']])->update($post1);
                }
                
            }else{
                if(isset($request['store_id']) && $request['store_id'] == Auth::user()->store_id){         
                    $userNewData['price'] = $request['price'];                   
                }
                $post1['store_id'] = Auth::user()->store_id;
                $post1['cheese_id'] = $request->id;
                $post1['custom_price'] = $request['price'];
            }
            $category->update($userNewData);
            if(!empty($post1)){
                StorePizzaCheese::updateOrCreate(['cheese_id'=>$post1['cheese_id'], 'store_id'=>$post1['store_id']], $post1);
            }
            $message = "Pizza Cheese successfully updated.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function createPizzaCheese($request)
    {
        DB::beginTransaction();
        try {

            $post['price'] = $request['price'];
            $post['pizza_size_master'] = $request['size_master_id'];

            if(isset($request->store_id)){
                $post['store_id'] = $request['store_id'];
            }
            $cheese = $this->PizzaExtraCheese->updateOrCreate(['id' => $request['id']], $post);
            if(isset($request->store_id)){
                $post1['store_id'] = $request['store_id'];
                $post1['cheese_id'] =$cheese->id;
                $post1['custom_price'] = $request['price'];

                StorePizzaCheese::updateOrCreate(['cheese_id'=>$post1['cheese_id'], 'store_id'=>$post1['store_id']], $post1);
            }
            DB::commit();
            $message = "Pizza Cheese added sucsessfully.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Change Cheese row status
    */
    public static function changePizzaCheeseStatus($request)
    {
        try {
            $cheeseData = PizzaExtraCheese::where(['id' => $request->id])->first();
            if (!empty($cheeseData)) {
                if(Auth::user()->user_type =='store'){
                    if(Auth::user()->store_id == $cheeseData['store_id']){
                        $storeCheeseData = StorePizzaCheese::where('store_id', Auth::user()->store_id)->where('cheese_id', $cheeseData['id'] )->update(['status' =>$request->status]);
                        $cheeseData->update(array('status' => $request->status));
                    }else{
                        $storeCheeseData = StorePizzaCheese::where('store_id', Auth::user()->store_id)->where('cheese_id', $cheeseData['id'])->update(['status' =>$request->status]);
                    }
                }else{
                    $cheeseData->update(array('status' => $request->status));
                }
                $message = 'Status successfully changed.';
                $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            } else {
                $response = ['success' => false, 'message' => 'Pizza Sauce does not found.', 'error' => [], 'data' => []];
            }
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function storeSizeSelection($request)
    {
        DB::beginTransaction();
        try {

            foreach($request->id as $v){

                $post['store_id'] = Auth::user()->store_id;
                $post['size_id'] = $v['id'];
                $post['custom_price'] = $v['price'];           
                StorePizzaSize::updateOrCreate(['size_id'=>$post['size_id'], 'store_id'=>$post['store_id']], $post);
            }
            DB::commit();
            $message = "Pizza Size added sucsessfully.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function storeCheeseSelection($request)
    {
        DB::beginTransaction();
        try {

            foreach($request->id as $v){

                $post['store_id'] = Auth::user()->store_id;
                $post['cheese_id'] = $v['id'];
                $post['custom_price'] = $v['price'];           
                StorePizzaCheese::updateOrCreate(['cheese_id'=>$post['cheese_id'], 'store_id'=>$post['store_id']], $post);
            }
            DB::commit();
            $message = "Pizza Cheese added sucsessfully.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
}
