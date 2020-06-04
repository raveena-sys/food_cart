<?php
namespace App\Repositories;

use App\EmailQueue\CreateEmployee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Auth;
use Datatables;
use App\Common\Helpers;
use App\Models\SidesMenu;
use \DB;

/**
 * Class EmployeeRepository.
 */

class SidesRepository
{
    private $sidesMenu;

    public function __construct(
        SidesMenu $sidesMenu
    ) {
        $this->sidesMenu = $sidesMenu;
    }


    public function getList($request)
    {
        try {

            $query = $this->sidesMenu->where('status', '!=', 'deleted')->where('store_id', Auth::user()->store_id);
            //->where(['user_type' => $request->type, 'user_role' => 'employee']);
            if (!empty($request->status)) {
                $query =  $query->where('status', $request->status);
            }
            /*if(Auth::user()->user_type =='store'){

                $query->join('store_category', function($join){
                        $join->on('store_category.cat_id', '=', 'category.id');
                        $join->where('store_category.store_id', '=', Auth::user()->store_id);
                    });


            }*/
            $sidesmenu = $query->orderby('id','desc')->get();
            $tableResult = Datatables::of($sidesmenu)->addIndexColumn()

                ->filter(function ($instance) use ($request) {

                    if (!empty($request->name)) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(Str::lower($row['sides_name']), Str::lower(trim($request->name))) ? true : false;
                        });
                    }
               
                })
                ->editColumn('name', function ($data) {                    
                    $userName = $data['sides_name'];
                    return $userName;
                })
                ->editColumn('description', function ($data) {
                    return $data['description'];
                })
                ->editColumn('price', function ($data) {
                    return $data['price'];
                })
               /* ->editColumn('store', function ($data) {
                    $price = !empty($data['store']['name'])?$data['store']['name']:'Admin';
                    return $price;
                })*/
              
                ->addColumn('status', function ($row) {
                    $show="onclick='changeStatus($row->id)'";
                    if(Auth::user()->user_type == 'store'){
                        $show = 'disabled';
                    }
                    $status = isset($row->status) ? $row->status : "";
                    $status = isset($row->status) ? $row->status : "";
                    $checked = ($status == 'active') ? "checked" : "";

                    $btn = "<div class='switch'> <label> <input class='onoffswitch-checkbox switchchange' name='onoffswitch' type='checkbox' data-status='$status' id='category$row->id' $checked  $show data-tableid='employee-lenderslist'> <span class='lever'></span> </label> </div>";
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    if(Auth::user()->user_type == 'store')
                    {
                        $viewUrl = url('store/manage-sides-menu/view/'.$row->id);
                    }else{
                        $viewUrl = url('admin/manage-sides-menu/view/'. $row->id);
                    }
                    
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
                ->rawColumns(['status', 'action', 'name', 'store'])
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
            $query = $this->sidesMenu->where('id', $id);
            /*if(Auth::user()->user_type == 'store'){
                $query->join('store_category', 'store_category.cat_id', '=', 'category.id')->where('store_category.store_id', Auth::user()->store_id);
                
            }*/
            $dips = $query->first();

            return $dips;
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

            $userData = $this->sidesMenu->where(['id' => $request->id])->select('id', 'name', 'description', 'status', 'store_id')->first();
            if (!empty($userData)) {
                if(Auth::user()->user_type =='store'){
                    if(Auth::user()->store_id == $userData['store_id']){
                        $userData->update(array('status' => 'deleted'));
                    }
                    $storeDipsData = StoreCategory::where('store_id', Auth::user()->store_id)->where('cat_id', $userData['id'] )->delete();                    
                }else{
                    $userData->update(array('status' => 'deleted'));
                }
                $message = 'Category successfully deleted.';
                $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            } else {
                $response = ['success' => false, 'message' => 'Category does not found.', 'error' => [], 'data' => []];
            }
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
            $post['description']    = $request['description'];
            $post['sides_name']           = $request['name'];
            $post['price']          = $request['price'];
           
            if(isset($request['store_id'])){
                $post['store_id']       = $request['store_id'];
            }
            $cat = $this->sidesMenu->updateOrCreate($post);
            /*if(isset($request['store_id'])){
                StoreCategory::updateorCreate(['cat_id'=>$cat->id, 'store_id' => $post['store_id']]);
            }*/

            DB::commit();
            $message = "Sides Menu added sucsessfully.";
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
            $userData = Category::where(['id' => $request->id])->select('id', 'name', 'description', 'status')->first();
            if (!empty($userData)) {
                $userData->update(array('status' => $request->status));
                $message = 'Status successfully changed.';
                $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            } else {
                $response = ['success' => false, 'message' => 'Cateogry does not found.', 'error' => [], 'data' => []];
            }
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }





    public function storeCategorySelection($request)
    {
        DB::beginTransaction();
        try {

            foreach($request->id as $v){

                $post['store_id'] = Auth::user()->store_id;
                $post['cat_id'] = $v['id'];          
                StoreCategory::updateOrCreate(['cat_id'=>$post['cat_id'], 'store_id'=>$post['store_id']], $post);
            }
            DB::commit();
            $message = "Category added sucsessfully.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
}
