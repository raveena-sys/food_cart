<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\User;
use App\Models\Job;
use App\Models\JobAssignment;
use Auth;
use Datatables;
use DB;
use App\Common\Helpers;
use App\EmailQueue\CreateIndividual;
use Illuminate\Support\Facades\Log;


/**
 * Class IndividualRepository.
 */
class IndividualRepository
{
    private $company,$user,$job,$jobAssignment;

    public function __construct(Company $company, User $user,Job $job,JobAssignment $jobAssignment)
    {
        $this->company = $company;
        $this->user = $user;
        $this->job = $job;
        $this->jobAssignment = $jobAssignment;

    }

    public function list($request)
    {
      try
        {

         $individual = $this->user->where('status', '!=', 'deleted')->where(['user_type'=>$request->user_type,'user_role'=>'individual']);
         if(!empty($request->status)){
            $individual->where('status',$request->status);
         }
        //  if(!empty($request->status)){
        //     $individual->where('status',$request->status);
        //  }
         $individual = $individual->latest()->get();
         $tableResult = Datatables::of($individual) ->addIndexColumn()
         ->filter(function ($instance) use ($request) {

            if (!empty($request->name)) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    return Str::contains(Str::lower($row['name']), Str::lower(trim($request->name))) ? true : false;
                });
            }
            if (!empty($request->total_earning)) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    return Str::contains(Str::lower($row['total_earning']), Str::lower(trim($request->total_earning))) ? true : false;
                });
            }
        })
       ->editColumn('name', function ($data) {
            $imageUrl = getUserImage($data['profile_image'],'users');
            $userName = $data['name'];
            $viewspath= ($data->user_type=='lender')?'admin/manage-individual/individual-lender-view/':'admin/manage-individual/individual-appraiser-view/';
            $viewUrl = url($viewspath . $data->id);
            $name = '<div class="user-img">
                        <img src="'.$imageUrl.'" alt="user-img" class="img-fluid rounded-circle">
                        </div>
                        <a href="'.$viewUrl.'" class="theme-color name">'.$userName.'</a>';
                        return $name;
                        //return ucfirst($individual->name);
        })
        ->editColumn('total_earning', function ($individual) {
            return 0;
        })
        ->addColumn('status', function($row) {

            $status = isset($row->status) ? $row->status : "";
            $checked = ($status == 'active') ? "checked" : "";
            $type= ($row->user_type=='lender')?1:2;
            $changestatus ="changeStatus($row->id,$type)";
            $btn = "<div class='switch'> <label> <input class='onoffswitch-checkbox switchchange' name='onoffswitch' type='checkbox'  data-status='$status' id='individual$row->id' $checked  onclick='".$changestatus."' data-tableid='individual-list'> <span class='lever'></span> </label> </div>";
            return $btn;
        })
        ->addColumn('action', function($row) {

               $viewspath= ($row->user_type=='lender')?'admin/manage-individual/individual-lender-view/':'admin/manage-individual/individual-appraiser-view/';
               $view = url($viewspath . $row->id);
               $edit ="editUserDetail($row->id)";
               $btn = '<div class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="icon-keyboard_control"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="'.$view.'">View</a>
                                <a class="dropdown-item" href="javascript:void(0);" onclick="'.$edit.'">Edit</a>
                                <a class="dropdown-item" href="javascript:void(0);"  id=remove'.$row->id.' data-tableid="individual-listing" onclick="deleteIndividual('.$row->id.')" >Delete</a>

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

    public function addIndiviudal($request)
    {
        DB::beginTransaction();
        try {
            $usr['email'] = $request['email'];
            $randomPass = generateRandomString(10);
            $usr['password']    = bcrypt($randomPass);
            $usr['phone_number_country'] = 'US';
            $usr['phone_number_country_code'] = !empty($request['phone_number_country_code']) ? $request['phone_number_country_code'] : '+1';
            $usr['phone_number']   = $request['phone_number'];
            $usr['user_type']      = $request['user_type'];
            $usr['user_role']      = $request['user_role'];
            $usr['name']           = $request['name'];
            $usr['created_by']     = Auth::user()->id;
            $usr['updated_by']     = Auth::user()->id;
            $user = $this->user->create($usr);
            $message = ucfirst($request['user_type'])." successfully added.";
            DB::commit();
            $emailData = array(
               'link' =>url(''),
               'name' =>$usr['name'],
               'email' =>$usr['email'],
               'password' =>$randomPass,
               'subject' =>'Add Individual '.ucfirst($request['user_type'])
            );
           CreateIndividual::dispatch($user,$emailData);

            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getIndividualDetails($id,$type)
    {
        try {
            $company =$this->user->where(['user_type'=>$type,'user_role'=>'individual','id'=>$id])->first();
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $company];
            return $response;
        }catch(\Exception $e)
        {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function updateUser($request)
    {
        DB::beginTransaction();
        try {
            $usr['email']       = $request['email'];
            $usr['name']       = $request['name'];
            $usr['phone_number_country'] = 'US';
            $usr['phone_number_country_code'] = !empty($request['phone_number_country_code']) ? $request['phone_number_country_code'] : 0;
            $usr['phone_number']   = $request['phone_number'];
            $this->user->where('id',$request['id'])->update($usr);
            $user = $this->user->where('id', $request->id)->first();
            $message = ucfirst($user->user_type)." successfully updated.";
            DB::commit();
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function viewCompanyDetails($id,$type)
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

    public function viewIndividualLenderDetails($id,$type)
    {
        try {
            $company =$this->user->where(['user_type'=>$type,'user_role'=>'individual','id'=>$id])->first();
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $company];
            return $response;
        }catch(\Exception $e)
        {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function viewIndividualAppraiserDetails($id,$type)
    {
        try {
            $company =$this->user->where(['user_type'=>$type,'user_role'=>'individual','id'=>$id])->first();
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $company];
            return $response;
        }catch(\Exception $e)
        {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function individualJobList($request)
    {
        try{
            if($request->role && $request->role=='lender'){
                $job = $this->jobAssignment->where('assigned_by',$request->individual_id)->with(['getJobDetail:id,title,address,job_state,status,description,created_at'])->latest();
                $job->whereHas('getJobDetail',function($q) use ($request){
                    $q->where('job_state',$request->type);
                 })
                 ->select('id','assigned_to','assigned_by','job_id','job_bid_id','status')
                 ->get();
            }else{
                $job = $this->jobAssignment->where('assigned_to',$request->individual_id)->with(['getJobDetail:id,title,address,job_state,status,description,created_at'])->latest();
                $job->whereHas('getJobDetail',function($q) use ($request){
                    $q->where('job_state',$request->type);
                 })
                 ->select('id','assigned_to','assigned_by','job_id','job_bid_id','status')
                 ->get();
            }


            $tableResult = Datatables::of($job) ->addIndexColumn()

            ->editColumn('posted_by', function ($data) {
                return $data->createdBy->name;
            })
            ->editColumn('posted_date', function ($data) {
                $currentDateTime = $data->getJobDetail->created_at;
                return date('Y-m-d h:i A', strtotime($currentDateTime));
            })
            ->editColumn('description', function ($data) {
                $description = preg_replace("/[^A-Za-z0-9 ]/", "", $data->getJobDetail->description);
                if(strlen($description) > 50){
                    $descriptionStr = substr($description,0, 50);
                    $description = $descriptionStr.'<a href="javascript:void(0);" data-decription='.$description.' id="description"'.$data->getJobDetail->id.'  title="" class="theme-color ml-1" onclick="readMoreModal('.$data->getJobDetail->id.')">Read More</a>';
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
}
