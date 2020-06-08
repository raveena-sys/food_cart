<?php

namespace App\Repositories;

use App\EmailQueue\CreateEmployee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use Auth;
use Datatables;
use App\Common\Helpers;
use App\Models\Category;
use App\Models\SubCategory;
use \DB;

/**
 * Class EmployeeRepository.
 */

class SubCategoryRepository
{
    private $SubCategory, $user, $category;

    public function __construct(
        User $user,
        SubCategory $SubCategory,
        Category $category
    ) {
        $this->user = $user;
        $this->SubCategory = $SubCategory;
        $this->category = $category;
    }


    public function getList($request)
    {
        try {
    
            $query = \DB::table('sub_category as subcat')->join('category as cat', 'subcat.category_id', '=', 'cat.id');
                if(Auth::user()->user_type=='store'){
                    
                    $query->join('store_category',function($join){
                        $join->on('store_category.cat_id', '=', 'cat.id');
                        $join->where('store_category.store_id', Auth::user()->store_id);
                    });
                }
                $query->where('subcat.status', '!=', 'deleted')
                ->select([
                    'subcat.id', 'subcat.name', 'subcat.status', 'subcat.description', 'subcat.thumb_image',
                    'cat.name as category_name', 'subcat.category_id as category_id'
                ]);
            

            if (!empty($request->status)) {
                $query->where('subcat.status', Str::lower(trim($request->status)));
            }

            if (!empty($request->category_id)) {
                $query->whereIN('subcat.category_id', $request->category_id);
            }
            $SubCategory = $query->orderby('subcat.id', 'desc')->get();
            $tableResult = Datatables::of($SubCategory)->addIndexColumn()

                ->filter(function ($instance) use ($request) {

                    if (!empty($request->name)) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(Str::lower($row['name']), Str::lower(trim($request->name))) ? true : false;
                        });
                    }
                    // if (!empty($request->category_id)) {
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //         return Str::contains(Str::lower($row->category_name), Str::lower(trim($request->category_id))) ? true : false;
                    //     });
                    // }
                    // if (!empty($request->manager)) {
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //         return Str::contains(Str::lower($row['manager']), Str::lower(trim($request->manager))) ? true : false;
                    //     });
                    // }
                })
                ->editColumn('name', function ($data) {
                    
                    $userName = $data->name;
                    
                    return $userName;
                })
                ->editColumn('description', function ($data) {
                    return $data->description;
                })
                ->editColumn('category_name', function ($data) {
                    return $data->category_name;
                })
                // ->editColumn('company', function ($category) {

                //     return (!empty($category->employeeDetails) ? ucfirst($category->employeeDetails->company->company_title) : '');
                // })
                // ->editColumn('manager', function ($category) {
                //     return (!empty($category->employeeDetails) ? ucfirst($category->employeeDetails->manager->managerInfo->name) : '');
                // })
                ->addColumn('status', function ($row) {

                    $status = isset($row->status) ? $row->status : "";
                    $checked = ($status == 'active') ? "checked" : "";

                    $btn = "<div class='switch'> <label> <input class='onoffswitch-checkbox switchchange' name='onoffswitch' type='checkbox' data-status='$status' id='subcategory$row->id' $checked  onclick='changeStatus($row->id)' data-tableid='employee-lenderslist'> <span class='lever'></span> </label> </div>";
                    return $btn;
                })
                ->addColumn('action', function ($row) {
              
                    if(Auth::user()->user_type == 'store')
                    {
                        $viewUrl = url('store/manage-sub-category/view/'.$row->id);
                    }else{
                        $viewUrl = url('admin/manage-sub-category/view/'. $row->id);
                    }
                    
                    $btn = "";
                    $btn = '<div class="dropdown">
                           <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <span class="icon-keyboard_control"></span>
                           </a>
                           <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                               <a class="dropdown-item" href="' . $viewUrl . '"   >View</a>
                               <a class="dropdown-item" href="javascript:void(0);" onclick="ediSubCategory(' . $row->id . ')">Edit</a>
                               <a class="dropdown-item" href="javascript:void(0);"  id=remove' . $row->id . ' data-url=' . url('admin/employee-delete') . ' data-name=' . $row->name . ' data-tableid="data-listing" onclick="deleteSubCategory(' . $row->id . ')" >Delete</a>
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
            return $this->SubCategory->where('id', $id)->first();
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

            $userData = SubCategory::where(['id' => $request->id])->select('id', 'name', 'description', 'status')->first();
            if (!empty($userData)) {
                $userData->update(array('status' => 'deleted'));
                $message = 'SubCategory successfully deleted.';
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
            $SubCategory = $this->SubCategory->where('id', $request->id);
            $userNewData = [
                'category_id' => $request['category_id'],
                'description' => $request['description'],
                'name' => $request['name'],
            ];
            $SubCategory->update($userNewData);

            $message = "SubCategory successfully updated.";
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
            $post['category_id'] = $request['category_id'];
            $post['description'] = $request['description'];
            $post['name'] = $request['name'];
            $post['created_by']     = $userData->id;
            $post['updated_by']     = $userData->id;

            $this->SubCategory->create($post);

            DB::commit();
            $message = "SubCategory added sucsessfully.";
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
            $userData = SubCategory::where(['id' => $request->id])->select('id', 'name', 'description', 'status')->first();
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


    public function getCategory()
    {
        $data = "<option value=''>Select Category</option>";
        $category = $this->category-- > where('status', '!=', 'deleted')->get();
        foreach ($category as $value) {
            $id = $value->id;
            $name = $value->name;
            $data .= "<option value='$id'>$name</option>";
        }
        return $data;
    }
}
