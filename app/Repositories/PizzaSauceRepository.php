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
use App\Models\PizzaSauceMaster;
use App\Models\StorePizzaSauce;
use \DB;

/**
 * Class EmployeeRepository.
 */

class PizzaSauceRepository
{
    private $PizzaSauce, $user;

    public function __construct(
        User $user,
        PizzaSauceMaster $PizzaSauce
    ) {
        $this->user = $user;
        $this->PizzaSauce = $PizzaSauce;
    }

    public function getList($request)
    {
        try {
            $PizzaSauce = $this->PizzaSauce->where('pizza_sauce_master.status', '!=', 'deleted');
            //->where(['user_type' => $request->type, 'user_role' => 'employee']);
            if(Auth::user()->user_type =='store'){

                $PizzaSauce->select('pizza_sauce_master.*', 'store_pizza_sauce.custom_price','store_pizza_sauce.status as s_status')->join('store_pizza_sauce', function($join){
                        $join->on('store_pizza_sauce.sauce_id', '=', 'pizza_sauce_master.id');
                        $join->where('store_pizza_sauce.store_id', '=', Auth::user()->store_id);
                    });

            }
            if (!empty($request->status)) {
                $PizzaSauce->where('pizza_sauce_master.status', $request->status);
            }
            $PizzaSauce = $PizzaSauce->latest()->get();
            $tableResult = Datatables::of($PizzaSauce)->addIndexColumn()

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
                    $userName = $data['name'];
                    // $viewUrl = url('admin/manage-PizzaSauce-view/' . $data->id);
                    // $name = '
                    // <div class="user-img">
                    //  <img src="' . $imageUrl . '" alt="user-img" class="img-fluid rounded-circle">
                    // </div>
                    //  <a  class="theme-color name">' . $userName . '</a>';
                    return $userName;
                    //href="' . $viewUrl . '"
                })
                ->editColumn('description', function ($data) {
                    return $data['description'];
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
                ->addColumn('action', function ($row) use ($request) {
                    $segment = $request->segment(1);
                    $viewUrl = url($segment .'/manage-pizza-sauce/view/' . $row->id);

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
            $query = $this->PizzaSauce->where('pizza_sauce_master.id', $id);
            if(Auth::user()->user_type == 'store'){
                $query->join('store_pizza_sauce', 'store_pizza_sauce.sauce_id', '=', 'pizza_sauce_master.id')->where('store_pizza_sauce.store_id', Auth::user()->store_id);
                $sauce = $query->select('pizza_sauce_master.*', 'store_pizza_sauce.custom_price as custom_price')->first();
            }
            else{
                
                $sauce = $query->select('pizza_sauce_master.*')->first();
            }
            return $sauce; 
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

            $userData = PizzaSauceMaster::where(['id' => $request->id])->select('id', 'name', 'description', 'status', 'store_id')->first();
            if (!empty($userData)) {
               if(Auth::user()->user_type =='store'){
                    if(Auth::user()->store_id == $userData['store_id']){
                        $storeSauceData = StorePizzaSauce::where('store_id', Auth::user()->store_id)->where('sauce_id', $userData['id'] )->delete();
                        $userData->update(array('status' => 'deleted'));
                    }else{
                        $storeSauceData = StorePizzaSauce::where('store_id', Auth::user()->store_id)->where('sauce_id', $userData['id'])->delete();
                    }
                }else{
                    $userData->update(array('status' => 'deleted'));
                }
                $message = 'Pizza Sauce successfully deleted.';
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
            $category = $this->PizzaSauce->where('id', $request->id);
            $userNewData = [
                'description' => $request['description'],
                'name' => $request['name'],
            ];

            $post1 =array();
            if(Auth::user()->user_type == 'admin'){
                $userNewData['price'] = $request['price'];
                if($request['storeid']){
                    $post1['custom_price'] = $request['price'];

                    StorePizzaSauce::where(['sauce_id'=>$request->id, 'store_id'=>$request['storeid']])->update($post1);
                }
                
            }else{
                if(isset($request['store_id']) && $request['store_id'] == Auth::user()->store_id){         
                    $userNewData['price'] = $request['price'];                   
                }
                $post1['store_id'] = Auth::user()->store_id;
                $post1['sauce_id'] = $request->id;
                $post1['custom_price'] = $request['price'];
                StorePizzaSauce::updateOrCreate(['sauce_id'=>$post1['sauce_id'], 'store_id'=>$post1['store_id']], $post1);
            }

            $category->update($userNewData);
            
            $message = "Pizza Sauce successfully updated.";
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
            $post['created_by']     = $userData->id;
            $post['updated_by']     = $userData->id;
            if(isset($request->store_id)){
                $post['store_id'] = $request['store_id'];
            }

            $sauce = $this->PizzaSauce->create($post);
            if(isset($request->store_id)){
                $post1['store_id'] = $request['store_id'];
                $post1['sauce_id'] =$sauce->id;
                $post1['custom_price'] = $request['price'];

                StorePizzaSauce::updateOrCreate(['sauce_id'=>$post1['sauce_id'], 'store_id'=>$post1['store_id']], $post1);
            }

            DB::commit();
            $message = "Pizza Sauce added sucsessfully.";
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
            $userData = PizzaSauceMaster::where(['id' => $request->id])->select('id', 'name', 'description', 'status','store_id')->first();
            if (!empty($userData)) {
                if(Auth::user()->user_type =='store'){
                    if(Auth::user()->store_id == $userData['store_id']){
                        $userData->update(array('status' => $request->status));
                    }
                   $storeSauceData = StorePizzaSauce::where('store_id', Auth::user()->store_id)->where('sauce_id', $userData['id'] )->update(['status' =>$request->status]);
                }else{
                    $userData->update(array('status' => $request->status));
                    $storeSauceData = StorePizzaSauce::where('store_id', $userData['store_id'])->where('sauce_id', $userData['id'] )->update(['status' =>$request->status]);
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

    public function storeSauceSelection($request)
    {
        DB::beginTransaction();
        try {

            foreach($request->id as $v){

                $post['store_id'] = Auth::user()->store_id;
                $post['sauce_id'] = $v['id'];
                $post['custom_price'] = $v['price'];           
                StorePizzaSauce::updateOrCreate(['sauce_id'=>$post['sauce_id'], 'store_id'=>$post['store_id']], $post);
            }
            DB::commit();
            $message = "Pizza Sauce added sucsessfully.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
}
