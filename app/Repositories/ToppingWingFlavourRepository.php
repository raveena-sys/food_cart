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
use App\Models\ToppingWingFlavour;
use App\Models\StoreToppingWing;
use \DB;

/**
 * Class EmployeeRepository.
 */

class ToppingWingFlavourRepository
{
    private $ToppingWingFlavour, $user;

    public function __construct(
        User $user,
        ToppingWingFlavour $ToppingWingFlavour
    ) {
        $this->user = $user;
        $this->ToppingWingFlavour = $ToppingWingFlavour;
    }

    public function getList($request)
    {
        try {
            $ToppingWingFlavour = $this->ToppingWingFlavour->where('topping_wing_flavour.status', '!=', 'deleted');
            //->where(['user_type' => $request->type, 'user_role' => 'employee']);
            if(Auth::user()->user_type =='store'){

                $ToppingWingFlavour->select('topping_wing_flavour.*', 'store_topping_wing.custom_price','store_topping_wing.status as s_status')->join('store_topping_wing', function($join){
                        $join->on('store_topping_wing.top_wing_id', '=', 'topping_wing_flavour.id');
                        $join->where('store_topping_wing.store_id', '=', Auth::user()->store_id);
                    });

            }
            if (!empty($request->status)) {
                $ToppingWingFlavour->where('topping_wing_flavour.status', $request->status);
            }
            $ToppingWingFlavour = $ToppingWingFlavour->latest()->get();
            $ToppingWingFlavour = Datatables::of($ToppingWingFlavour)->addIndexColumn()

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
                    return   $data['description'];
                })
                ->editColumn('food_type', function ($data) {
                    return   ucwords(str_replace('_', ' ', $data['food_type']));
                })
                ->editColumn('price', function ($data) {
                    if(Auth::user()->user_type == 'store')
                    {
                        return round($data->custom_price,2);
                    }else{
                        return round($data->price,2);
                    }
                })

                // ->editColumn('size_name', function ($data) {
                //     return $data->size_name;
                // })
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
                ->addColumn('action', function ($row) use($request){
                    $segment = $request->segment(1);
                    $viewUrl = url($segment.'/manage-topping-wing-flavour/view/' . $row->id);

                    $btn = "";
                    $btn = '<div class="dropdown">
                           <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <span class="icon-keyboard_control"></span>
                           </a>
                           <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                               <a class="dropdown-item" href="' . $viewUrl . '"   >View</a>
                               <a class="dropdown-item" href="javascript:void(0);" onclick="ediCategory(' . $row->id . ')">Edit</a>
                               <a class="dropdown-item" href="javascript:void(0);"  id=remove' . $row->id . ' data-url=' . url($segment.'/employee-delete') . ' data-name=' . $row->name . ' data-tableid="data-listing" onclick="deleteCategory(' . $row->id . ')" >Delete</a>
                           </div>
                       </div>';
                    return $btn;
                })
                // <a class="dropdown-item" href="javascript:void(0);" onclick="'.$delete.'">Delete</a>
                ->rawColumns(['status', 'action', 'name'])
                ->make(true);

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $ToppingWingFlavour];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getDetail($id)
    {
        try {
            $query =  $this->ToppingWingFlavour->where('topping_wing_flavour.id', $id);
            if(Auth::user()->user_type == 'store'){
                $query->join('store_topping_wing', 'store_topping_wing.top_wing_id', '=', 'topping_wing_flavour.id')->where('store_topping_wing.store_id', Auth::user()->store_id);
                $crust = $query->select('topping_wing_flavour.*', 'store_topping_wing.custom_price as custom_price')->first();
            }
            else{
                $crust = $query->select('topping_wing_flavour.*')->first();
            }

            return $crust; 
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

            $userData = ToppingWingFlavour::where(['id' => $request->id])->select('id', 'name', 'description', 'status', 'store_id')->first();
            if (!empty($userData)) {
                if(Auth::user()->user_type =='store'){
                    if(Auth::user()->store_id == $userData['store_id']){
                        $userData->update(array('status' => 'deleted'));
                    }
                    $storeCrustData = StoreToppingWing::where('store_id', Auth::user()->store_id)->where('top_wing_id', $userData['id'] )->delete();
                }else{
                    $userData->update(array('status' => 'deleted'));
                }
                $message = 'Topping Wing Flavour successfully deleted.';
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
            $category = $this->ToppingWingFlavour->where('id', $request->id);
            $userNewData = [
                'description' => $request['description'],
                'name' => $request['name'],
                'food_type' => $request['food_type'],
                //'price' => $request['price'],
            ];

            $post1 =array();
            if(Auth::user()->user_type == 'admin'){
                $userNewData['price'] = $request['price'];
                if($request['storeid']){
                    $post1['custom_price'] = $request['price'];

                    StoreToppingWing::where(['top_wing_id'=>$request->id, 'store_id'=>$request['storeid']])->update($post1);
                }
                
            }else{
                if(isset($request['store_id']) && $request['store_id'] == Auth::user()->store_id){         
                    $userNewData['price'] = $request['price'];                   
                }
                $post1['store_id'] = Auth::user()->store_id;
                $post1['top_wing_id'] = $request->id;
                $post1['custom_price'] = $request['price'];
                if(!empty($post1)){
                    StoreToppingWing::updateOrCreate(['top_wing_id'=>$post1['top_wing_id'], 'store_id'=>$post1['store_id']], $post1);
                }
            }
            $category->update($userNewData);

            $message = "Topping wing flavour successfully updated.";
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
            $post['food_type'] = $request['food_type'];
            $post['price'] = $request['price'];

            $post['created_by']     = $userData->id;
            $post['updated_by']     = $userData->id;
            if(isset($request->store_id)){
                $post['store_id'] = $request['store_id'];
            }

            $crust = $this->ToppingWingFlavour->create($post);
            if(isset($request->store_id)){
                $post1['store_id'] = $request['store_id'];
                $post1['top_wing_id'] =$crust->id;
                $post1['custom_price'] = $request['price'];

                StoreToppingWing::updateOrCreate(['top_wing_id'=>$post1['top_wing_id'], 'store_id'=>$post1['store_id']], $post1);
            }
            DB::commit();
            $message = "Topping wing flavour added sucsessfully.";
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
            $userData = ToppingWingFlavour::where(['id' => $request->id])->select('id', 'name', 'description', 'status', 'store_id')->first();
            if (!empty($userData)) {
                if(Auth::user()->user_type =='store'){
                    if(Auth::user()->store_id == $userData['store_id']){
                        $userData->update(array('status' => $request->status));
                    }
                    $storeCrustData = StoreToppingWing::where('store_id', Auth::user()->store_id)->where('top_wing_id', $userData['id'] )->update(['status' =>$request->status]);
                }else{
                    $userData->update(array('status' => $request->status));
                }
                $message = 'Status successfully changed.';
                $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            } else {
                $response = ['success' => false, 'message' => 'Topping Pizza does not found.', 'error' => [], 'data' => []];
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


    public function storeToppingWingsSelection($request)
    {
        DB::beginTransaction();
        try {

            foreach($request->id as $v){

                $post['store_id'] = Auth::user()->store_id;
                $post['top_wing_id'] = $v['id'];
                $post['custom_price'] = $v['price'];           
                StoreToppingWing::updateOrCreate(['top_wing_id'=>$post['top_wing_id'], 'store_id'=>$post['store_id']], $post);
            }
            DB::commit();
            $message = "Pizza Topping wing added sucsessfully.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
}
