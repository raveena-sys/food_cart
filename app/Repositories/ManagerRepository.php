<?php

namespace App\Repositories;
use App\EmailQueue\CreateManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Manager;
use App\Models\User;
use App\Models\Job;
use App\Models\Employee;
use App\Models\JobBid;
use App\Models\JobAssignment;
use Auth;
use Datatables;
use \DB;

/**
 * Class CompanyRepository.
 */
class ManagerRepository
{
    private $company, $user, $manager, $job,$jobAssignment,$jobBid;

    public function __construct(Company $company, User $user, Manager $manager, Employee $employee, Job $job,JobAssignment $jobAssignment,JobBid $jobBid)
    {
        $this->company = $company;
        $this->user = $user;
        $this->manager = $manager;
        $this->employee = $employee;
        $this->job = $job;
        $this->jobAssignment = $jobAssignment;
        $this->jobBid = $jobBid;
    }
    /**
     * To ge list of manager
     * @param type $request
     * @return string|array
     */
    public function managerList($request)
    {
        try {

            $manager = $this->user->where('status', '!=', 'deleted')
                ->where('user_type', $request->type)
                ->where(function ($q) use ($request) {
                    if (!empty($request->name)) {
                        $q->where('name', 'like', '%' . $request->name . '%');
                    }
                })
                ->where('user_role', 'manager');

            if (!empty($request->status)) {
                $manager->where('status', $request->status);
            }
            $manager = $manager->latest()->get();

            $tableResult = Datatables::of($manager)->addIndexColumn()

                ->filter(function ($instance) use ($request) {
                    if (!empty($request->company)) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(Str::lower($row['company']), Str::lower(trim($request->company))) ? true : false;
                        });
                    }
                })
                ->editColumn('name', function ($data) {
                    $imageUrl = getUserImage($data['profile_image'], 'users');
                    $userName = $data['name'];
                    $viewUrl = url('admin/manage-manager/manager-view/' . $data->id);
                    $name = '<div class="user-img">
                        <img src="' . $imageUrl . '" alt="user-img" class="img-fluid rounded-circle">
                        </div>
                        <a href="' . $viewUrl . '" class="theme-color name">' . $userName . '</a>';
                    return $name;
                })
                ->editColumn('company', function ($manager) {
                    return (!empty($manager->managerDetails) ? ucfirst($manager->managerDetails->company->company_title) : '');
                })
                ->addColumn('status', function ($row) {

                    $status = isset($row->status) ? $row->status : "";
                    $checked = ($status == 'active') ? "checked" : "";

                    $btn = "<div class='switch'> <label> <input class='onoffswitch-checkbox switchchange' name='onoffswitch' type='checkbox' data-status='$status' id='manager$row->id' $checked  onclick='changeStatus($row->id)' data-tableid='manager-lenderslist'> <span class='lever'></span> </label> </div>";
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $viewUrl = url('admin/manage-manager/manager-view/' . $row->id);
                    $btn = '<div class="dropdown">
               <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <span class="icon-keyboard_control"></span>
               </a>
               <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                   <a class="dropdown-item" href="' . $viewUrl . '">View</a>
                   <a class="dropdown-item" href="javascript:void(0);" id=edit' . $row->id . '  onclick="editLenderManager(' . $row->id . ')">Edit</a>
                   <a class="dropdown-item" href="javascript:void(0);"  id=remove' . $row->id . ' data-name=' . $row->name . ' data-tableid="manager-listing" onclick="deleteManager(' . $row->id . ')" >Delete</a>
               </div>
           </div>';
                    return $btn;
                })
                // <a class="dropdown-item" href="javascript:void(0);" onclick="'.$delete.'">Delete</a>
                ->rawColumns(['action', 'name', 'status'])
                ->make(true);

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
    /**
     * To add manager
     * @param type $request
     * @return string
     */
    public function createManager($request)
    {
        DB::beginTransaction();
        try {
            $randomPass = generateRandomString(10);
            $usr['password']    = bcrypt($randomPass);
            $usr['email'] = $request['email'];
            $usr['name'] = $request['name'];
            $usr['phone_number_country'] = 'US';
            $usr['phone_number_country_code'] = !empty($request['phone_number_country_code']) ? $request['phone_number_country_code'] : 0;
            $usr['phone_number']   = $request['phone_number'];
            $usr['user_type']      = $request['user_type'];
            $usr['user_role']      = $request['user_role'];
            $usr['created_by']     = Auth::user()->id;
            $usr['updated_by']     = Auth::user()->id;
            $user       = $this->user->create($usr);

            $user_id    = $user->id;
            $manager['company_id']   = $request['company'];
            $manager['user_id']         = $user_id;
            $this->manager->create($manager);
            /**
             * sending mail
             */
            DB::commit();

            $emailData = array(
                'link' => url(''),
                'name' => $usr['name'],
                'email' => $usr['email'],
                'password' => $randomPass
            );
            CreateManager::dispatch($user,$emailData);

            $message =  "Manager successfully added.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function viewCompanyDetails($id)
    {
        try {
            $company = $this->user->with('companyDetails')->where('status', '!=', 'deleted')->where('user_type', 'lender')->where('user_role', 'company')->where('id', $id)->first();
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $company];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To delete manager
     * @param type $id
     * @return boolean|array
     */
    public function deleteCompanyUser($id)
    {
        try {
            $this->user->where('id', $id)->update(['status' => 'deleted']);
            $response = ['success' => true, 'message' => 'Manger deleted successfully.', 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get manager detail
     * @param type $id
     * @return string
     */
    public function managerDetail($id)
    {
        try {
            return $this->user->where('id', $id)->first();
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get manager employee list
     * @param type $request
     * @return string
     */
    public function managerEmployeeList($request)
    {
        try {
            $employee = $this->employee->with(['manager', 'employeeDetail'])->latest();
            $employee = $employee->whereHas('manager', function ($q) use ($request) {
                $q->where('user_id', '=', $request->manager_id);
            });
            $employee = $employee->whereHas('employeeDetail', function ($q) use ($request) {
                $q->where('status', '!=', 'deleted');
            });
            $employee = $employee->get();

            $tableResult = Datatables::of($employee)->addIndexColumn()
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
                })
                ->editColumn('name', function ($data) {
                    $imageUrl = getUserImage($data->employeeDetail->profile_image, 'users');
                    $userName = $data->employeeDetail->name;
                    $viewUrl = url('admin/manage-employee/employee-view/' . $data->employeeDetail->id);
                    $name = '<div class="user-img">
                              <img src="' . $imageUrl . '" alt="user-img" class="img-fluid rounded-circle">
                            </div>
                            <a href="' . $viewUrl . '" class="theme-color name">' . $userName . '</a>';
                    return $name;
                })
                ->editColumn('email', function ($data) {
                    return $data->employeeDetail->email;
                })
                ->editColumn('company', function ($employee) {
                    return (!empty($employee->company) ? ucfirst($employee->company->company_title) : '');
                })
                ->editColumn('manager_name', function ($employee) {
                    return (!empty($employee->manager) ? ucfirst($employee->manager->managerInfo->name) : '');
                })
                ->addColumn('custom_status', function ($row) {
                    $status = isset($row->employeeDetail->status) ? $row->employeeDetail->status : "";
                    $checked = ($status == 'active') ? "checked" : "";
                    $btn = "<div class='switch'> <label> <input class='onoffswitch-checkbox switchchange' name='onoffswitch' type='checkbox'  data-url='" . url('admin/employee-status') . "' data-status='$status' id='employee$row->user_id' $checked  onclick='changeStatus($row->user_id)' data-tableid='manager-lenderslist'> <span class='lever'></span> </label> </div>";
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $type = 'users';
                    $viewUrl = url('admin/manage-employee/employee-view/' . $row->user_id);
                    $btn = '<div class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="icon-keyboard_control"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="' . $viewUrl . '">View</a>
                                    <a class="dropdown-item" href="javascript:void(0);" id=edit' . $row->user_id . ' onclick="editLenderManagerEmployee(' . $row->user_id . ')">Edit</a>
                                    <a class="dropdown-item" href="javascript:void(0);"  id=remove' . $row->user_id . ' data-tableid="listingEmployees" onclick="deleteManager(' . $row->user_id . ')" >Delete</a>
                                </div>
                            </div>';
                    return $btn;
                })
                // <a class="dropdown-item" href="javascript:void(0);" onclick="'.$delete.'">Delete</a>
                ->rawColumns(['custom_status', 'action', 'name'])
                ->make(true);

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getManagerDetails($id, $type)
    {
        try {

            $manager = $this->user->with('managerDetails')->where(['user_type' => $type, 'user_role' => 'manager', 'id' => $id])->first();
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $manager];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getEmployeeDetails($id, $type)
    {
        try {

            $manager = $this->user->with('employeeDetails')->where(['user_type' => $type, 'user_role' => 'employee', 'id' => $id])->first();
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $manager];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To update manager detail
     * @param type $request
     * @return boolean
     */
    public function EditManager($request)
    {
        try {
            $this->user->where('id', $request->id)->update(['name' => $request->name, 'email' => $request->email, 'phone_number' => $request->phone_number]);
            $message = "Manger Detail updated successfully.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To update manager detail
     * @param type $request
     * @return boolean
     */
    public function EditEmployee($request)
    {
        try {
            $this->user->where('id', $request->id)->update(['name' => $request->name, 'email' => $request->email, 'phone_number' => $request->phone_number]);
            $this->employee->where(['id' => $request->employee_id])->update(['manager_id' => $request->manager]);
            $message = "Employee Detail updated successfully.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }


    /**
     * To get inprgress contracts list
     * @param type $request
     * @return string
     */
    public function contractsList($request)
    {

        try {
            if($request->role=='lender'){
                $job = $this->job->where('created_by', $request->manager_id)->where('job_state', $request->type)->where('status','active')->latest();
            }else{
                $job = $this->job->where('status', '!=', 'deleted')->where('job_state', $request->type)->with(['jobBidss','jobAssignments']);
                $job->whereHas('jobBidss',function($q1) use($request){
                    $q1->where('user_id',$request->manager_id);
                    $q1->where('status','accepted');
               });
                $job->whereHas('jobAssignments',function($q1) use($request){
                        $q1->where('assigned_to',$request->manager_id);
                        $q1->where('status',  '=', 'active');
                });
            }

            $job = $job->get();
            $tableResult = Datatables::of($job)->addIndexColumn()

                ->editColumn('posted_by', function ($data) {
                    return $data->createdBy->name;
                })
                ->editColumn('posted_date', function ($data) {
                    $currentDateTime = $data->created_at;
                    return date('d/m/y h:i A', strtotime($currentDateTime));
                })
                ->editColumn('description', function ($data) {
                    $description = $data->description;
                    if (strlen($description) > 50) {
                        $descriptionStr = substr($description, 0, 50);
                        $description = $descriptionStr . '<a href="javascript:void(0);" data-decription=' . $description . ' id="description"' . $data->id . '  title="" class="theme-color ml-1" onclick="readMoreModal(' . $data->id . ')">Read More</a>';
                    }
                    return $description;
                })
                ->rawColumns(['posted_by', 'posted_date', 'description'])
                ->make(true);

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get manager employee detail
     * @param type $id
     * @return string
     */
    public function managerEmployeeDetail($id)
    {
        try {
            return $this->user->where('id', $id)->first();
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To update manager detail
     * @param type $request
     * @return boolean
     */
    public function updateManager($request)
    {
        try {
            $this->user->where('id', $request->id)->update(['name' => $request->name, 'phone_number' => $request->phone_number, 'email' => $request->email]);
            // $user = $this->user->where('id', $request->id)->first();
            $message = "Manager successfully updated.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get manager list
     * @param type $request
     * @return boolean
     */
    public function getManagersList($request)
    {
        try {
            //$list = $this->user->where('status', '!=', 'deleted')->where(['user_type'=>$request->user_type,'user_role'=>'manager'])->with('managerDetails')->latest();
            $activeContractSql = "SELECT COUNT(ua.id) FROM job_assignments as ua
        JOIN jobs AS j ON j.id=ua.job_id WHERE ua.assigned_to=users.id AND ua.status='active' AND j.job_state='in_progress'";
            $list = $this->user
                ->selectRaw("users.*, ($activeContractSql) AS active_contracts")
                ->where('status', '!=', 'deleted')->where(['user_type' => $request->user_type, 'user_role' => 'manager'])->with('managerDetails')->latest();

            $list->whereHas('managerDetails', function ($q) {
                $q->where('company_id', '=', Auth::user()->companyDetails->id);
            });

            if (isset($request['total_job']) && $request['total_job']) {
                $list->where('name', 'like', '%' . $request['total_job'] . '%');
            }

            if (!empty($request['active_contracts'])) {
                $activeSearchParam = $request['active_contracts'];
                $list->whereRaw("($activeContractSql) <= $activeSearchParam");
             }
            if ($request['registration_month'] == 'this_month') {
                $monthFirstDay =  date('Y-m-01');
                $monthLastDay =  date('Y-m-t');
                $from =  date("Y-m-d", strtotime($monthFirstDay));
                $to =  date("Y-m-d", strtotime($monthLastDay));
                $list->whereBetween('created_at', [$from, $to]);
            }

            if ($request['registration_month'] == 'last_month') {
                $monthFirstDay =  date('Y-m-d', strtotime(date('Y-m-01') . ' -1 MONTH'));;
                $monthLastDay =  date('Y-m-d', strtotime(date('Y-m-t') . ' -1 MONTH'));;
                $from =  date("Y-m-d", strtotime($monthFirstDay));
                $to =  date("Y-m-d", strtotime($monthLastDay));
                $list->whereBetween('created_at', [$from, $to]);
            }
            if (isset($request['status']) && $request['status']) {
                $list->where('status', '=', $request['status']);
            }
            if (!empty($request['search'])) {
                $list->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request['search'] . '%')
                        ->orwhere('email', 'like', '%' . $request['search'] . '%');
                });
            }

            $list = $list->paginate(10);
            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getTotalManagerList($request)
    {
        $list = $this->user->where('status', '!=', 'deleted')->where(['user_type' => $request->user_type, 'user_role' => 'manager'])->with('managerDetails');
        $list->whereHas('managerDetails', function ($q) {
            $q->where('company_id', '=', Auth::user()->companyDetails->id);
        });
        return $list->get();
    }


    public function getActiveManagerList($request)
    {
        $list = $this->user->where('status', '=', 'active')->where(['user_type' => $request->user_type, 'user_role' => 'manager'])->with('managerDetails');
        $list->whereHas('managerDetails', function ($q) {
            $q->where('company_id', '=', Auth::user()->companyDetails->id);
        });
        return $list->get();
    }

    public function getTotalCompanyManagerList()
    {
        if (Auth::user()->user_type == 'appraiser') {
            $list = $this->user->where('status', '!=', 'deleted')->where(['user_type' => 'appraiser', 'user_role' => 'manager'])->with('managerDetails');
            $list->whereHas('managerDetails', function ($q) {
                $q->where('company_id', '=', Auth::user()->companyDetails->id);
            });
            return $list->get();
        } else {
            $list = $this->user->where('status', '!=', 'deleted')->where(['user_type' => 'lender', 'user_role' => 'manager'])->with('managerDetails');
            $list->whereHas('managerDetails', function ($q) {
                $q->where('company_id', '=', Auth::user()->companyDetails->id);
            });
            return $list->get();
        }
    }
}
