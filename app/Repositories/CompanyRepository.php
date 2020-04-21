<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\User;
use Auth;
use Datatables;
use DB;
use App\Common\Helpers;
use App\EmailQueue\CreateCompany;

/**
 * Class CompanyRepository.
 */
class CompanyRepository
{
    private $company,$user;

    public function __construct(Company $company, User $user)
    {
        $this->company = $company;
        $this->user = $user;

    }
    public function list($request)
    {
      try
        {

         $company = $this->user->with('companyDetails')->where('status', '!=', 'deleted')->where(['user_type'=>$request->user_type,'user_role'=>'company']);
         if(!empty($request->status)){
            $company->where('status',$request->status);
         }
         if(!empty($request->company_title)){
            $company->whereHas('companyDetails',function($q) use($request){
            //    $q->where('company_title',$request->company_title);
               $q->where('company_title', 'like', '%'.$request->company_title .'%');
            });
         }
         $company=$company->latest()->get();
         $tableResult = Datatables::of($company) ->addIndexColumn()->filter(function ($instance) use ($request) {

            // if (!empty($request->company_title)) {
            //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
            //         return Str::contains(Str::lower($row['company_title']), Str::lower(trim($request->company_title))) ? true : false;
            //     });
            // }
            if (!empty($request->company_strength)) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    return Str::contains(Str::lower($row['company_strength']), Str::lower(trim($request->company_strength))) ? true : false;
                });
            }
        })
        ->editColumn('company_title', function ($company) {
            $viewspath= ($company->user_type=='lender')?'admin/manage-company/lender/':'admin/manage-company/appraiser/';
            $userName = ($company->companyDetails)?ucfirst($company->companyDetails->company_title):'-';
             $view = url($viewspath . $company->id);
            $name = '<a href="'.$view.'" class="theme-color name">'.$userName.'</a>';
            return $name;
        })
        ->editColumn('company_strength', function ($company) {
            return ($company->companyDetails)?ucfirst($company->companyDetails->company_strength):'-';
        })
        ->addColumn('status', function($row) {

            $status = isset($row->status) ? $row->status : "";
            $checked = ($status == 'active') ? "checked" : "";

            $btn = "<div class='switch'> <label> <input class='onoffswitch-checkbox switchchange' name='onoffswitch' type='checkbox' data-status='$status' id='company$row->id' $checked  onclick='changeStatus($row->id)' data-tableid='company-list'> <span class='lever'></span> </label> </div>";
            return $btn;
        })
        ->addColumn('action', function($row) {

               $viewspath= ($row->user_type=='lender')?'admin/manage-company/lender/':'admin/manage-company/appraiser/';
               $view = url($viewspath . $row->id);
               $edit ="editCompanies($row->id)";
               $btn = '<div class="dropdown">
               <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <span class="icon-keyboard_control"></span>
               </a>
               <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                   <a class="dropdown-item" href="'.$view.'">View</a>
                   <a class="dropdown-item" href="javascript:void(0);" onclick="'.$edit.'">Edit</a>
                   <a class="dropdown-item" href="javascript:void(0);"  id=remove'.$row->id.' data-url=' . url('admin/manager-delete') . ' data-tableid="company-listing" onclick="deleteCompany('.$row->id.')" >Delete</a>
               </div>
           </div>';
            return $btn;
        })
       ->rawColumns(['status','action','company_title'])
        ->make(true);

        $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
        return $response;

        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
    public function addCompany($request)
    {
        DB::beginTransaction();
        try {
            $usr['email'] = $request['email'];
            $randomPass = rand(111111,999999);
            $usr['password']    = bcrypt($randomPass);
            $usr['phone_number_country'] = 'US';
            $usr['phone_number_country_code'] = !empty($request['phone_number_country_code']) ? $request['phone_number_country_code'] : 0;
            $usr['phone_number']   = $request['phone_number'];
            $usr['user_type']      = $request['user_type'];
            $usr['user_role']      = $request['user_role'];
            $usr['name']   =       $request['company_title'];
            $usr['created_by']     = Auth::user()->id;
            $usr['updated_by']     = Auth::user()->id;
            $user       = $this->user->create($usr);
            $user_id    = $user->id;

            $cmp['company_title']   = $request['company_title'];
            $cmp['founded_year']    = $request['founded_year'];
            $cmp['user_id']         = $user_id;
            $cmp['company_strength']= $request['company_strength'];

            $this->company->create($cmp);
            $message = ucfirst($request['user_type'])." company successfully added.";
            DB::commit();

            $emailData = array(
                'link' => url(''),
                'name' => $usr['name'],
                'email' => $usr['email'],
                'password' => $randomPass
            );
            CreateCompany::dispatch($user,$emailData);
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
    public function getCompanyDetails($id,$type)
    {
        try {
            $company =$this->user->with('companyDetails')->where(['user_type'=>$type,'user_role'=>'company','id'=>$id])->first();
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $company];
            return $response;
        }catch(\Exception $e)
        {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
    public function updateCompanyUser($request)
    {
        DB::beginTransaction();
        try {
            $usr['email']       = $request['email'];
            $usr['phone_number_country'] = 'US';
            $usr['phone_number_country_code'] = !empty($request['phone_number_country_code']) ? $request['phone_number_country_code'] : 0;
            $usr['phone_number']   = $request['phone_number'];
            $this->user->where('id',$request['id'])->update($usr);


            $cmp['company_title']   = $request['company_title'];
            $cmp['founded_year']    = $request['founded_year'];
            $cmp['company_strength']= $request['company_strength'];
            $this->company->where('user_id',$request['id'])->update($cmp);
            $user = $this->user->where('id', $request->id)->first();
            $message = ucfirst($user->user_type)." company successfully updated.";
            DB::commit();
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }


    public function viewCompanyLenderDetails($id,$type)
    {
        try {
            $company =$this->user->with('companyDetails')->where(['user_type'=>$type,'user_role'=>'company','id'=>$id])->first();
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $company];
            return $response;
        }catch(\Exception $e)
        {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
    public function viewCompanyApraiserDetails($id,$type)
    {
        try {
            $company =$this->user->with('companyDetails')->where(['user_type'=>$type,'user_role'=>'company','id'=>$id])->first();
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $company];
            return $response;
        }catch(\Exception $e)
        {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function companyEmployeeList($request)
    {
     try
     {
         $company = $this->user->whereHas('employeeDetails', function ($q) use ($request) {
            $q->where('company_id',$request->company_id);

        });
        if(!empty($request->manager_id))
            {
                $company->whereHas('employeeDetails', function ($q1) use ($request) {
                    if(!empty($request->manager_id))
                    {
                        $q1->where('manager_id',$request->manager_id);
                    }
                });
            }
            $company = $company->where('status', '!=', 'deleted')->where(['user_type'=>$request->user_type,'user_role'=>'employee'])->latest()->get();

         $tableResult = Datatables::of($company) ->addIndexColumn()
         ->addColumn('name', function ($data) {
            $imageUrl = getUserImage($data['profile_image'],'users');
            $userName = $data['name'];
            $viewspath= 'admin/manage-employee/employee-view/';
            $view = url($viewspath . $data->id);
            $name = '<div class="user-img">
            <img src="'.$imageUrl.'" alt="user-img" class="img-fluid rounded-circle">
            </div>
            <a href="'.$view.'" class="theme-color name">'.$userName.'</a>';
              return $name;
              })

            ->editColumn('company', function ($employee) {

                return (!empty($employee->employeeDetails) ? ucfirst($employee->employeeDetails->company->company_title) :'');
            })
            ->editColumn('manager', function ($employee) {
                return (!empty($employee->employeeDetails) ? ucfirst($employee->employeeDetails->manager->managerInfo->name) :'');
            })
        ->addColumn('status', function($row) {

            $status = isset($row->status) ? $row->status : "";
            $checked = ($status == 'active') ? "checked" : "";

            $btn = "<div class='switch'> <label> <input class='onoffswitch-checkbox switchchange' name='onoffswitch' type='checkbox' data-status='$status' id='user$row->id' $checked  onclick='changeStatus($row->id)' data-tableid='listEmployeesListView'> <span class='lever'></span> </label> </div>";
            return $btn;
        })
        ->addColumn('action', function($row) {

               $viewspath= ($row->user_type=='lender')?'admin/manage-employee/employee-view/':'admin/manage-employee/employee-view/';
               $view = url($viewspath . $row->id);
               $edit ="editCompanyEmployee($row->id)";
               $btn = '<div class="dropdown">
               <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <span class="icon-keyboard_control"></span>
               </a>
               <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                   <a class="dropdown-item" href="'.$view.'">View</a>
                   <a class="dropdown-item" href="javascript:void(0);"  id=edit'.$row->user_id.' onclick="'.$edit.'">Edit</a>
                </div>
           </div>';
            return $btn;
        })
        // <a class="dropdown-item" href="javascript:void(0);" onclick="'.$delete.'">Delete</a>
        ->rawColumns(['name','status','action'])
        ->make(true);

        $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
        return $response;

        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    // public function companyLenderEmployeeView($id)
    // {
    //     try {
    //          $company =$this->user->where(['user_role'=>'employee','id'=>$id])->first();
    //         $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $company];
    //         return $response;
    //     }catch(\Exception $e)
    //     {
    //         $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
    //         return $response;
    //     }
    // }

    // public function companyAppraiserEmployeeView($id)
    // {
    //     try {
    //          $company =$this->user->where(['user_role'=>'employee','id'=>$id])->first();
    //         $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $company];
    //         return $response;
    //     }catch(\Exception $e)
    //     {
    //         $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
    //         return $response;
    //     }
    // }

    public function companyManagerList($request)
    {
        try
        {
         $company = $this->user->whereHas('managerDetails', function ($q) use ($request) {
            $q->where('company_id',$request->company_id);

        });
         $company = $company->where('status', '!=', 'deleted')->where(['user_type'=>$request->user_type,'user_role'=>'manager'])->latest()->get();
         $tableResult = Datatables::of($company) ->addIndexColumn()
         ->addColumn('name', function ($data) {
            $imageUrl = getUserImage($data['profile_image'],'users');
            $userName = $data['name'];
            $viewspath= ($data->user_type=='lender')?'admin/manage-manager/manager-view/':'admin/manage-manager/manager-view/';
            $view = url($viewspath . $data->id);
            $name = '<div class="user-img">
            <img src="'.$imageUrl.'" alt="user-img" class="img-fluid rounded-circle">
            </div>
            <a href="'.$view.'" class="theme-color name">'.$userName.'</a>';
              return $name;
              })

            ->editColumn('company', function ($employee) {

                return (!empty($employee->managerDetails) ? ucfirst($employee->managerDetails->company->company_title) :'');
            })
          ->addColumn('status', function($row) {

            $status = isset($row->status) ? $row->status : "";
            $checked = ($status == 'active') ? "checked" : "";

            $btn = "<div class='switch'> <label> <input class='onoffswitch-checkbox switchchange' name='onoffswitch' type='checkbox'  data-status='$row->status' id='user$row->id' $checked  onclick='changeStatus($row->id)' data-tableid='listManagersListView'> <span class='lever'></span> </label> </div>";
            return $btn;
        })
        ->addColumn('action', function($row) {

               $viewspath= ($row->user_type=='lender')?'admin/manage-manager/manager-view/':'admin/manage-manager/manager-view/';
               $view = url($viewspath . $row->id);
               $edit ="editCompanyManager($row->id)";
               $btn = '<div class="dropdown">
               <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <span class="icon-keyboard_control"></span>
               </a>
               <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                   <a class="dropdown-item" href="'.$view.'">View</a>
                   <a class="dropdown-item" href="javascript:void(0);" onclick="'.$edit.'">Edit</a>

               </div>
           </div>';
            return $btn;
        })
        // <a class="dropdown-item" href="javascript:void(0);" onclick="'.$delete.'">Delete</a>
        ->rawColumns(['name','status','action'])
        ->make(true);

        $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
        return $response;

        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    // public function companyLenderManagerView($id)
    // {
    //     try {
    //          $company =$this->user->where(['user_role'=>'manager','id'=>$id])->first();
    //         $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $company];
    //         return $response;
    //     }catch(\Exception $e)
    //     {
    //         $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
    //         return $response;
    //     }
    // }

    // public function companyAppraiserManagerView($id)
    // {
    //     try {
    //          $company =$this->user->where(['user_role'=>'manager','id'=>$id])->first();
    //         $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $company];
    //         return $response;
    //     }catch(\Exception $e)
    //     {
    //         $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
    //         return $response;
    //     }
    // }

    public function updateCompanyAbout($request)
    {
        try {
            $upd['company_about']= $request['company_description'];
            $this->company->where('id', $request['company_id'])->update($upd);
            $response = ['success' => true, 'message' => 'Company About Us content updated successfully', 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function updateUserAbout($request)
    {
        try {
            $upd['about_me']= $request['company_description'];
            $this->user->where('id', Auth::user()->id)->update($upd);
            $response = ['success' => true, 'message' => 'User About Us content updated successfully', 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getEmployeeList($request)
    {
        try
        {

            $company = $this->company->where('user_id',Auth::user()->id)->select('id')->first();

            $employee = $this->user->whereHas('employeeDetails', function ($q) use ($company) {
               $q->where('company_id',$company->id);

           });

               $employee = $employee->where('status', '!=', 'deleted')->where(['user_type'=>'lender','user_role'=>'employee'])->latest()->paginate(10);
               $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $employee];
               return $response;
            } catch (\Exception $e) {
                $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
                return $response;
            }
    }
    public function getManagerList($request)
    {

        try
        {
            $company = $this->company->where('user_id',Auth::user()->id)->select('id')->first();
            $request['company_id']=$company->id;
            $manager = $this->user->whereHas('managerDetails', function ($q) use ($request) {
                $q->where('company_id',$request->company_id);

            });
             $manager = $manager->where('status', '!=', 'deleted')->where(['user_role'=>'manager'])->latest()->paginate(10);
             $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $manager];
             return $response;
            } catch (\Exception $e) {
                $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
                return $response;
            }
    }
 /**
     * Manager profile company lender
     * @param $id of manager
     * @return type
     */
    public function companyLenderManagerDetails($id)
    {
        try {
             $company =$this->user->where(['user_role'=>'manager','id'=>$id])->first();
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $company];
            return $response;
        }catch(\Exception $e)
        {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
 /**
     * employee profile company lender
     * @param $id of manager
     * @return type
     */
    public function companyLenderEmployeeDetails($id)
    {
        try {
             $employee =$this->user->where(['user_role'=>'employee','id'=>$id])->first();
             $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $employee];
            return $response;
        }catch(\Exception $e)
        {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

        /**
     * To get company employee detail
     * @param type $id
     * @return string
     */
    public function companyEmployeeDetail($id){
        try {
        return $this->user->where('id', $id)->first();
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

        /**
     * To get company manager detail
     * @param type $id
     * @return string
     */
    public function companyManagerDetail($id){
        try {
        return $this->user->where('id', $id)->first();
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
}
