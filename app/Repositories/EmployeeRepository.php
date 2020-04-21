<?php

namespace App\Repositories;
use App\EmailQueue\CreateEmployee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\JobBid;
use App\Models\JobAssignment;
use App\Models\User;
use Auth;
use Datatables;
use App\Common\Helpers;
use App\Models\Job;
use \DB;

/**
 * Class EmployeeRepository.
 */

class EmployeeRepository
{
     private $company,$user,$employee,$manager,$job,$jobAssignment,$jobBid;

    public function __construct(Company $company,User $user,Employee $employee,Manager $manager,Job $job,JobAssignment $jobAssignment,JobBid $jobBid)
    {
        $this->company = $company;
        $this->user = $user;
        $this->employee = $employee;
        $this->manager = $manager;
        $this->job = $job;
        $this->jobAssignment = $jobAssignment;
        $this->jobBid = $jobBid;

    }


    public function employeeList($request)
    {
        try
        {

         $employee = $this->user->where('status', '!=', 'deleted')->where(['user_type'=>$request->type,'user_role'=>'employee']);
         if(!empty($request->status)){
            $employee->where('status',$request->status);
         }
         $employee = $employee->latest()->get();
         $tableResult = Datatables::of($employee) ->addIndexColumn()

         ->filter(function ($instance) use ($request) {

            if (!empty($request->name)) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    return Str::contains(Str::lower($row['name']), Str::lower(trim($request->name))) ? true : false;
                });
            }
            if (!empty($request->company)) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    return Str::contains(Str::lower($row['company']), Str::lower(trim($request->company))) ? true : false;
                });
            }
            if (!empty($request->manager)) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    return Str::contains(Str::lower($row['manager']), Str::lower(trim($request->manager))) ? true : false;
                });
            }


        })
        ->editColumn('name', function ($data) {
          $imageUrl = getUserImage($data['profile_image'],'users');
          $userName = $data['name'];
          $viewUrl = url('admin/manage-employee/employee-view/' . $data->id);
          $name = '<div class="user-img">
          <img src="'.$imageUrl.'" alt="user-img" class="img-fluid rounded-circle">
          </div>
          <a href="'.$viewUrl.'" class="theme-color name">'.$userName.'</a>';
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

            $btn = "<div class='switch'> <label> <input class='onoffswitch-checkbox switchchange' name='onoffswitch' type='checkbox' data-status='$status' id='employee$row->id' $checked  onclick='changeStatus($row->id)' data-tableid='employee-lenderslist'> <span class='lever'></span> </label> </div>";
            return $btn;
        })
        ->addColumn('action', function($row) {
               $viewUrl = url('admin/manage-employee/employee-view/' . $row->id);
               $btn = '<div class="dropdown">
               <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <span class="icon-keyboard_control"></span>
               </a>
               <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                   <a class="dropdown-item" href="'.$viewUrl.'">View</a>
                   <a class="dropdown-item" href="javascript:void(0);" onclick="editEmployee('.$row->id.')">Edit</a>
                   <a class="dropdown-item" href="javascript:void(0);"  id=remove'.$row->id.' data-url=' . url('admin/employee-delete') . ' data-name='.$row->name.' data-tableid="employee-listing" onclick="deleteEmployee('.$row->id.')" >Delete</a>
               </div>
           </div>';
            return $btn;
        })
        // <a class="dropdown-item" href="javascript:void(0);" onclick="'.$delete.'">Delete</a>
        ->rawColumns(['status','action','name'])
        ->make(true);

        $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
        return $response;

        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }



    public function employeeDetail($id){
        try {
           return $this->user->where('id', $id)->first();
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function employeeJobList($request){

        try{
            if($request->role=='lender'){
                $job = $this->job->where('created_by',$request->employee_id)->where('job_state',$request->type)->where('status','active')->latest();
            }else{
                $job = $this->job->where('job_state',$request->type)->where('status','active')->with(['jobAssignments'])->latest();
                $job->whereHas('jobAssignments', function ($q) use ($request) {
                    $q->where('status',  '=', 'active')
                        ->where('assigned_to', $request->employee_id);
                });
                $job->whereHas('jobBidss', function ($q) use ($request) {
                    $q->where('user_id', $request->employee_id)
                        ->where('status', 'accepted');
                });

            }
            $job =$job->get();
            $tableResult = Datatables::of($job) ->addIndexColumn()

            ->editColumn('posted_by', function ($data) {
                return $data->createdBy->name;
            })
            ->editColumn('posted_date', function ($data) {
                $currentDateTime = $data->created_at;
                return date('d/m/y h:i A', strtotime($currentDateTime));
            })
            ->editColumn('description', function ($data) {
                $description = preg_replace("/[^A-Za-z0-9 ]/", "", $data->description);
                if(strlen($description) > 50){
                    $descriptionStr = substr($description,0, 50);
                    $description = $descriptionStr.'<a href="javascript:void(0);" data-decription='.$description.' id="description"'.$data->id.'  title="" class="theme-color ml-1" onclick="readMoreModal('.$data->id.')">Read More</a>';
                }
                return $description;
            })
            ->rawColumns(['posted_by','posted_date', 'description'])
            ->make(true);

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
            return $response;

        } catch (\Exception $e) {

            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getManagers($id)
    {

       $data = "<option value=''>Select Manager</option>";
       $managers = $this->manager->where('company_id',$id)->get();
        foreach($managers as $value){
            $id = $value->id;
            $manager = $value->managerInfo->name;
                $data .= "<option value='$id'>$manager</option>";
        }
        return $data;
    }

    public function updateEmployee($request){
        try {
           $user = $this->user->where('id', $request->id);
           $userNewData = [
                'email' => $request['email'],
                'name' => $request['name'],
                'phone_number' => $request['phone_number'],
            ];
           $user->update($userNewData);

            if(!empty($request->manager_id)){
                $employeeData =  $this->employee->where(['user_id' => $request->id]);
                $empNewData = [
                    'manager_id' => $request->manager_id
                  ];
                $employeeData->update($empNewData);
            }
           $message = "Employee successfully updated.";
           $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
           return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function createEmployee($request)
    {
        DB::beginTransaction();
        try {
            $randomPass = generateRandomString(10);
            $userData = Auth::user();
            $post['email'] = $request['email'];
            $post['password'] = bcrypt($randomPass);
            $post['name'] = $request['name'];
            $post['phone_number_country'] = 'US';
            $post['phone_number_country_code'] = !empty($request['phone_number_country_code']) ? $request['phone_number_country_code'] : '+1';
            $post['phone_number']   = $request['phone_number'];
            $post['user_type']      = $request['user_type'];
            $post['user_role']      = $request['user_role'];
            $post['created_by']     = $userData->id;
            $post['updated_by']     = $userData->id;
            $user       = $this->user->create($post);
            if( $userData->user_role == 'company'){
                $company = $this->company->where(['user_id' => $userData->id])->first();
                $employee['company_id']   = $company->id;
                $employee['manager_id']   = $request['manager_id'];
            }else{
                $employee['company_id']   = $request['company'];
                $employee['manager_id']   = $request['manager_id'];
            }
            $employee['user_id']         = $user->id;

            $this->employee->create($employee);
            DB::commit();
            $emailData = array(
                    'link' => url(''),
                    'name' => $post['name'],
                    'email' => $post['email'],
                    'password' => $randomPass
                );
            CreateEmployee::dispatch($user,$emailData);

            $message = "Employee added sucsessfully.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getEmployeeList($request)
    {
        try{
            $activeContractSql ="";
            if(Auth::user()->user_type == 'appraiser'){
                $activeContractSql = "SELECT COUNT(ua.id) FROM job_assignments as ua
                JOIN jobs AS j ON j.id=ua.job_id WHERE ua.assigned_to=users.id AND ua.status='active' AND j.job_state='in_progress'";

            }else{
                $activeContractSql = "SELECT COUNT(ua.id) FROM job_assignments as ua
                JOIN jobs AS j ON j.id=ua.job_id WHERE ua.assigned_by=users.id AND ua.status='active' AND j.job_state='in_progress'";
            }

            $list = $this->user->selectRaw("users.*, ($activeContractSql) AS active_contracts")
                                 ->where('status', '!=', 'deleted')->where(['user_type'=>$request->user_type,'user_role'=>'employee'])->with(['employeeDetails','companyDetails'])->latest();

            if(Auth::user()->user_role == "manager"){
                $list->whereHas('employeeDetails', function($q) {
                    $q->where('manager_id', '=', Auth::user()->managerDetails->id);
                });
            }else{
                $list->whereHas('employeeDetails', function($q) {
                    $q->where('company_id', '=', Auth::user()->companyDetails->id);
                });
            }

            if (!empty($request['active_contracts'])) {
               $activeSearchParam = $request['active_contracts'];
               $list->whereRaw("($activeContractSql) <= $activeSearchParam");
            }

            if (isset($request['registration_date']) && $request['registration_date']) {
               $list->where('created_at','>=' , date('Y-m-d', strtotime($request['registration_date'])).' 00:00:00' );
               $list->where('created_at','<=' , date('Y-m-d', strtotime($request['registration_date'])).' 23:59:59' );
            }

            if ($request['registration_month'] == 'this_month') {
                $firstday =  date("Y-m-d", strtotime("first day of this month"));
                $lastday =  date("Y-m-d", strtotime("last day of this month"));
                $list->whereBetween('created_at', [$firstday, $lastday]);
            }
            if ($request['registration_month'] == 'last_month') {
                $firstday =  date("Y-m-d", strtotime("first day of last month"));
                $lastday =  date("Y-m-d", strtotime("last day of last month"));
                $list->whereBetween('created_at', [$firstday, $lastday]);
            }

            if (isset($request['status']) && $request['status']) {
                $list->where('status', '=', $request['status']);
            }
            if (!empty($request['search_title'])) {
                $list->where(function($q) use($request) {
                            $q->where('name', 'like', '%' . $request['search_title'] . '%')
                            ->orwhere('email', 'like', '%' . $request['search_title'] . '%');
                });
                //    $list->where('name', 'like', '%' . $request['search_title'] . '%')
                //          ->orWhere('email', 'like', '%' . $request['search_title'] . '%');
             }

            $list = $list->paginate(10);
            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getActiveEmployeeList(){
        $list = $this->user->where('status', '=', 'active')->where(['user_role'=>'employee'])->with(['employeeDetails','companyDetails']);
        if(Auth::user()->user_role == "manager"){
            $list->whereHas('employeeDetails', function($q) {
                     $q->where('manager_id', '=', Auth::user()->managerDetails->id);
            });
        }else{
            $list->whereHas('employeeDetails', function($q) {
                $q->where('company_id', '=', Auth::user()->companyDetails->id);
           });
        }
        return $list->get();
    }
    public function getTotalEmployeeList($request){
        $list = $this->user->where('status', '!=', 'deleted')->where(['user_type'=>$request->user_type,'user_role'=>'employee'])->with(['employeeDetails','companyDetails']);
        if(Auth::user()->user_role == "manager"){
            $list->whereHas('employeeDetails', function($q) {
                     $q->where('manager_id', '=', Auth::user()->managerDetails->id);
            });
        }else{
            $list->whereHas('employeeDetails', function($q) {
                $q->where('company_id', '=', Auth::user()->companyDetails->id);
           });
        }

      return $list->get();
    }

    public  function getManagerEmployeeList($request)
    {
        $post = $request->all();
        $manager = $this->manager->where('user_id',$post['id'])->first();
        if(Auth::user()->user_type == 'lender'){
            $activeContractSql = "SELECT COUNT(ua.id) FROM job_assignments as ua
            JOIN jobs AS j ON j.id=ua.job_id WHERE ua.assigned_by=users.id AND ua.status='active' AND j.job_state='in_progress'";

            $list = $this->user->selectRaw("users.*, ($activeContractSql) AS active_contracts")
            ->where('status', '!=', 'deleted')->where(['user_type'=>$post['user_type'],'user_role'=>'employee'])->with(['employeeDetails'])->latest();
        }else{
            $list = $this->user->where('status', '!=', 'deleted')->where(['user_type'=>$post['user_type'],'user_role'=>'employee'])->with(['employeeDetails'])->latest();
        }

            $list->whereHas('employeeDetails', function($q) use ( $manager) {
            $q->where('manager_id', '=', $manager->id);

        });


        if (!empty($request['query'])) {
             $list->where('name', 'like', '%' . $request['query'] . '%')
                  ->Where('email', 'like', '%' . $request['query'] . '%');
        }

        $list = $list->paginate(10);
        return $list;

}
}

