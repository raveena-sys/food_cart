<?php

namespace App\Repositories;

use App\EmailQueue\CreateVerifyAccount;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Models\User;
use App\Models\SocialLink;
use App\Models\StoreMaster;
use File;
use DB;
use DatePeriod;
use DateInterval;
use Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

//use Your Model

/**
 * Class UserRepository.
 */
class UserRepository
{

    /**
     * @return string
     *  Return the model
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * For admin login
     * @param type $request
     * @return type
     */
    public function login($request)
    {
        try {
            $remember_me = $request->has('remember_me') ? true : false;
            
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'user_type' => 'admin', 'status' => 'active'], $remember_me)) {
                    if ($remember_me) {
                        setcookie('email', $request->email, time() + (365 * 24 * 60 * 60));
                        setcookie("password", $request->password, time() + (365 * 24 * 60 * 60));
                    } else {
                        if (isset($_COOKIE["email"])) {
                            setcookie("email", "");
                        }
                        if (isset($_COOKIE["password"])) {
                            setcookie("password", "");
                        }
                    }

                    return ['success' => true, 'message' => 'Login successfully.', 'error' => [], 'data' => []];
                }

            return ['success' => false, 'message' => 'Invalid credential.', 'error' => [], 'data' => []];
        } catch (\Exception $ex) {
            return ['success' => false, 'message' => $ex->getMessage(), 'error' => [], 'data' => []];
        }
    }

    /**
     * For front user login
     * @param type $request
     * @return type
     */
    public function frontlogin($request)
    {

        try {

            session(['timezone' =>$request->timezone]);
            $user = $this->user->where('email', $request->email)->where('user_type', '!=', 'admin')->first();

            if (!empty($user) && ($user['status'] == 'pending')) {
                return ['success' => false, 'message' => 'Please verify account first.', 'error' => [], 'data' => []];
            }
            if (!empty($user) && ($user['status'] == 'inactive')) {
                return ['success' => false, 'message' => 'Your account is inactive,Please contact admin.', 'error' => [], 'data' => []];
            }
            if (!empty($user) && Auth::attempt(['email' => $user->email, 'password' => $request->password, 'status' => 'active'])) {
                return ['success' => true, 'message' => 'Login successfully.', 'error' => [], 'data' => Auth::user()];
            }
            return ['success' => false, 'message' => 'Invalid credential.', 'error' => [], 'data' => []];
        } catch (\Exception $ex) {
            return ['success' => false, 'message' => $ex->getMessage(), 'error' => [], 'data' => []];
        }
    }

    /**
     * To update profile
     * @param type $request
     * @return type
     */
    public function updateProfile($request)
    {
        try {
            $user = Auth::user();
            $fileName = "";
            $profilePath = public_path() . '/uploads/users';
            if (!is_dir($profilePath)) {
                File::makeDirectory($profilePath, $mode = 0777, true, true);
            }
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $fileName = $file->getClientOriginalName();
                $fileExtension = strtolower($file->getClientOriginalExtension());
                $name = time().'.'.$fileExtension;
                $imageExist = public_path() . '/uploads/users/' . $name;
                $request->file('profile_image')->move($profilePath, $name);
                $user->profile_image = $name;
            }
            $user->name = $request->name;
            $user->save();
            $segment = $request->segment(1);
            return redirect($segment.'/profile-setting')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function signup($request)
    {
        DB::beginTransaction();
        try {
            $confirmToken = str_random(30);
            $referralCode = generateReferralCode(10);
            $post = $request->all();
            if ($post['user_role'] == 'employee') {
                $post['company_id'] = $post['employee_company_id'];
            }
            if ($post['user_role'] == 'manager') {
                $post['company_id'] = $post['manager_company_id'];
            }
            if ($post['user_role'] == 'company') {
                $post['name'] = $post['company_title'];
            }
            $pass = $post['password'];
            $post['password'] = bcrypt($pass);
            $post['verify_token'] = $confirmToken;
            $post['referral_code'] = $referralCode;
            $post['status'] = 'pending';

            $user = $this->user->create($post);
            //Check refferal code
            if (!empty($post['referrer_code'])) {
                $data = [];
                $referrerUserData = $this->user->where('status', 'active')->where('referral_code', $post['referrer_code'])->first();
                if (!empty($referrerUserData)) {
                    $referrerData = [];
                    $user->referred_by = $referrerUserData->referral_code;
                    $user->save();
                    //Check referrer user payment status
                    $checkSubscriptionPayment = $this->userSubscriptionPayment->where('payment_status', 'success')->where('user_id', $referrerUserData->id)->first();
                    if (!empty($checkSubscriptionPayment)) {
                        $referrerData['status'] = 'approved';
                    }
                    $referrerData['from_id'] = $referrerUserData->id;
                    $referrerData['to_id'] = $user->id;
                    $this->userReferral->create($referrerData);
                }
            }

            $post['user_id'] = $user->id;
            if ($user->user_role == 'manager') {
                $this->manager->create($post);
            } elseif ($user->user_role == 'employee') {
                $this->employee->create($post);
            } elseif ($user->user_role == 'company') {
                $this->company->create($post);
            }

            DB::commit();
            $emailData = array(
                'link' =>url('verify-account/' . $confirmToken),
                'name' => $user->name,
                'email' => $user->email
           );

            CreateVerifyAccount  ::dispatch($user,$emailData);
            return ['success' => true, 'message' => "You're almost done. We've sent a verification email to the address you provided. Clicking the confirmation link in that email let us know the email address is both valid and yours. It is also your final step in the sign-up process.", 'error' => [], 'data' => []];
        } catch (\Exception $ex) {
            DB::rollback();
            return ['success' => false, 'message' => $ex->getMessage(), 'error' => [], 'data' => []];
        }
    }

    /**
     * To change store password
     * @param type $request
     * @return type
     */
    public function updatePassword($request)
    {
        try {
            $user = Auth::user();
            $user->update(array('password' => bcrypt($request['new_password'])));
            $segment = $request->segment(1);
            return redirect($segment.'/change-password')->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return json_encode(array('success' => false, 'message' => $e->getMessage()));
        }
    }

    public function verifyAccount($token)
    {
        try {
            $user = $this->user->where('verify_token', $token)->first();
            if ($user) {
                $user->update(['verify_token' => '', 'status' => 'active']);
                return redirect('/')->with('success', 'Your account successfully verified.');
            } else {
                return redirect('/')->with('error', 'Verification link has been expired.');
            }
        } catch (\Exception $e) {
            return json_encode(array('success' => false, 'message' => $e->getMessage()));
        }
    }


    /**
     * get appraiser list
     * @param type $request
     * @return type
     */
    public function getAppraisers($request)
    {
       try {
            $post = $request->all();
            $data = \DB::table('users as us')
                    ->join('reviews as rev', 'rev.to_id', '=', 'us.id')
                    ->join('job_bids as jb', 'jb.user_id', '=', 'us.id')->where('jb.status', '=', 'accepted')
                    ->select(['us.id','us.name','us.email','us.phone_number_country','us.phone_number_country_code','us.phone_number','us.profile_image','rev.id as revId','rev.rating',\DB::raw('AVG(rev.rating) AS average_rating')])
                    ->groupBy('us.id');

           if (isset($post['search_title']) && $post['search_title']) {
                $data->where('name', 'like', '%' . $post['search_title'] . '%');
            }

            if (!empty($post['rating'])) {
                $data->havingRaw('CAST(AVG(rev.rating) as UNSIGNED) = ?',[$post['rating']]);

            }

            return $data = $data->paginate(10);
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
            return $response;
        }
    }

    public function getAllCompanies($type)
    {

        $companyList = $this->user->select('id', 'name')->where('user_type', $type)->where('user_role', 'company')->with('companyDetails')->get();
        //$data = ["<option value=''>Select Company</option>"];
        // foreach ($companyList as $value) {
        //     $data[] = "<option value='$value->id'>".$value->companyDetails->company_title."</option>";
        // }
        return $companyList;
    }

    public function getManagers($id)
    {
        $managers = $this->manager->where('company_id', $id)->get();

        foreach ($managers as $value) {
            $id = $value->id;
            $manager = $value->managerInfo->name;
            $data[] = "<option value='$id'>$manager</option>";
        }
        return $data;
    }

    public function getUserDetails()
    {
        try {
            $data = Auth::user();
            $users = $this->user->where(['user_type' => $data->user_type, 'id' => $data->id]);
            $companyData = ""; //company_about
            if ($data->user_role == 'company') {
                $companyData = ($data->companyDetails) ? $data->companyDetails : '';
            }
            if ($data->user_role == 'employee') {
                $companyData = ($data->employeeDetails) ? $data->employeeDetails->company : '';
            }
            if ($data->user_role == 'manager') {
                $companyData = ($data->managerDetails) ? $data->managerDetails->company : '';
            }
            $users = $users->first();
            // $users['companyAbout'] = $companyAbout;
            $users['companyDetail'] = $companyData;
            // $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $users];
            return $users;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public static function aboutUserDetails()
    {
        try {
            $data = Auth::user();
            $users = User::where(['user_type' => $data->user_type, 'id' => $data->id]);
            $companyData = ""; //company_about
            if ($data->user_role == 'company') {
                $companyData = ($data->companyDetails) ? $data->companyDetails : '';
            }
            if ($data->user_role == 'employee') {
                $companyData = ($data->employeeDetails) ? $data->employeeDetails->company : '';
            }
            if ($data->user_role == 'manager') {
                $companyData = ($data->managerDetails) ? $data->managerDetails->company : '';
            }
            $users = $users->first();
            // $users['companyAbout'] = $companyAbout;
            $users['companyDetail'] = $companyData;
            // $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $users];
            return $users;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To update profile
     * @param type $request
     * @return type
     */
    public function updateSetting($request)
    {
        try {
            $user = Auth::user();
            $fileName = "";

            $profilePath = public_path() . '/' . config('constants.UPLOAD_PATH') . '/' . config('constants.PROFILE_PATH');
            if (!is_dir($profilePath)) {
                File::makeDirectory($profilePath, $mode = 0777, true, true);
            }
            if ($request->hasFile('profile_image')) {
                removeProfileImage($user->profile_image);
                $file = $request->file('profile_image');
                $fileExtension = strtolower($file->getClientOriginalExtension());
                $fileName = time() . "profile_img" . '.' . $fileExtension;
                $request->file('profile_image')->move($profilePath, $fileName);
                $user->profile_image = $fileName;
            }
            $user->name = ($user->user_role == 'company') ? $request->company_title : $request->name;
            $user->email =   $request->email;
            $user->phone_number =  $request->phone_number;
            $user->save();

            if ($user->user_role == 'company') {
                $upd['company_title'] = $request->company_title;
                $usId = Auth::user()->id;
                $this->company->where(['user_id' => $usId])->update($upd);
            }

            $response = ['success' => true, 'message' => 'Profile update successfully', 'error' => [], 'data' => $user];
            return $response;
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * To change admin password
     * @param type $request
     * @return type
     */
    public function changePassword($request)
    {
        try {
            $user = Auth::user();
            $user->update(array('password' => bcrypt($request['new_password'])));
            $response = ['success' => true, 'message' => 'Password updated successfully', 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            return json_encode(array('success' => false, 'message' => $e->getMessage()));
        }
    }

    public function changeVisibility($request)
    {
        try {
            $isVisibility = ($request->status == 0) ? 1 : 0;
            $user = Auth::user();
            $user->update(array('is_visibility' => $isVisibility));
            $response = ['success' => true, 'message' => 'Visibility status successfully changed.', 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            return json_encode(array('success' => false, 'message' => $e->getMessage()));
        }
    }

    public function updateAvailability($request)
    {
        try {
            $date = date('Y-m-d', strtotime($request['date']));
            $user = $this->userAvailability->where(['date' => $date, 'user_id' => Auth::user()->id])->first();
            if ($request['status'] == 'available') {
                $availability = $this->userAvailability->where(['date' => $date, 'user_id' => Auth::user()->id])->first();
                $availability->delete();
                $message = "Availability successfully deleted.";
            }
            if ($user) {
                $this->userAvailability->where(['date' => $date, 'user_id' => Auth::user()->id])->update(['type' => $request['status']]);
                $message = "Availability successfully updated.";
            } else {
                $usr['date'] = date("Y-m-d", strtotime($request['date']));
                $usr['type'] = $request['status'];
                $usr['user_id'] = Auth::user()->id;
                $this->userAvailability->create($usr);
                $message = "Availability successfully added.";
            }
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getAvailability($date)
    {
        try {
            $user_id = Auth::user()->id;
            $data = $this->userAvailability->where('user_id', $user_id)->where('date', $date)->first();
            if ($data) {
                $response = ['success' => true, 'message' => 'Availability successfully updated.', 'error' => [], 'data' => $data];
            } else {
                $response = ['success' => false, 'message' => 'Availability not found.', 'error' => [], 'data' => []];
            }

            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getNextAvailability($date)
    {
        try {
            $data = [];
            $user_id = Auth::user()->id;
            $date = [];
            $newDate = [];
            $mostRecent = 0;
            $availability = $this->userAvailability->where('user_id', $user_id)->get()->toArray();
            if (!empty($availability)) {
                foreach ($availability as $key => $avl) {
                    $offerArray[$key] = $avl['date'];
                    $curDate = $avl['date'];
                    if ($curDate > $mostRecent) {
                        $mostRecent = $curDate;
                    }
                    if ($mostRecent) {
                        $newDate = date('Y-m-d', strtotime($mostRecent . ' + 1 days'));
                    if($newDate > date('Y-m-d')){
                        $newDate = date("d M Y", strtotime($newDate));

                        $getAvailableDates = findMissedDates($offerArray);
                        if($getAvailableDates['first_missed_date'] > date('Y-m-d')){
                        if (!empty($getAvailableDates['missed_dates'])) {
                            $data['date'] = date("d M Y", strtotime($getAvailableDates['first_missed_date']));
                        } else {
                            $data['date'] = $newDate;
                        }
                    }else {
                        $data['date'] = $newDate;
                    }
                    }else{
                        $data['date'] = date("d M Y");
                    }

                    }
                }
            } else {
                $data['date'] = date("d M Y");
            }
            $response = ['success' => true, 'message' => 'Next Availability found.', 'error' => [], 'data' => $data];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
    /**
     * To get user availability
     * @param type $request
     * @return string
     */
    public function getUserAvailability($request)
    {
        try {
            $currentDate = date('Y-m-d');
            $availabilityData = $this->userAvailability->select('id', 'type', 'date')->where('user_id', $request->id)->where('date', '>=', $currentDate)->get();
            $response = ['success' => true, 'message' => '.', 'error' => [], 'data' => $availabilityData];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get job detail in appraiser side
     * @param type $request
     * @return string
     */
    public function getAppraiserContractUserList($request)
    {
        $post = $request->all();
        try {
            $fromDate = '';
            $toDate = '';
            $listArray = [];
            $whereIn = ['employee'];
            if (Auth::user()->user_role == 'company') {
                $whereIn = ['manager', 'employee'];
            }

            $list = $this->user->whereIn('user_role', $whereIn)
                ->where('is_visibility', 1)->where('status', 'active')->with(['employeeDetails', 'managerDetails']);

            if (Auth::user()->user_role == 'manager') {
                $managerDetail = $this->manager->where('user_id', Auth::user()->id)->first();
                $list->whereHas('employeeDetails', function ($q) use ($post, $managerDetail) {
                    $q->where('manager_id', $managerDetail->id);
                });
            } else if (Auth::user()->user_role == 'company') {
                $companyDetail = $this->company->where('user_id', Auth::user()->id)->first();
                $list->where(function ($q) use ($post, $companyDetail) {

                    $q->orWhereHas('employeeDetails', function ($q) use ($post, $companyDetail) {
                        $q->where("company_id", $companyDetail->id);
                    });
                    $q->orWhereHas('managerDetails', function ($q) use ($post, $companyDetail) {
                        $q->where("company_id", $companyDetail->id);
                    });
                });
            }

            //Serach from name or email condition
            if (!empty($post['q'])) {
                $list->whereRaw('(name like "%' . $post['q'] . '%" OR email like "%' . $post['q'] . '%")');
            }
            //Search from availability
            if (!empty($post['next_availability'])) {

                if ($post['next_availability'] == 'this_week') {
                    $fromDate = date('Y-m-d');
                    $toDate = date('Y-m-d', strtotime('sunday this week'));
                } else if ($post['next_availability'] == 'next_week') {
                    $fromDate = date('Y-m-d', strtotime('monday next week'));
                    $toDate = date('Y-m-d', strtotime('sunday next week'));
                } else if ($post['next_availability'] == 'this_month') {
                    $fromDate = date('Y-m-d');
                    $toDate = date('Y-m-t');
                }
                if ($fromDate && $toDate) {
                    $list = $list->get();
                }
            } else {
                $list = $list->paginate(10);
            }

            //Search from user role
            if (!empty($post['role'])) {
                $list->where('user_role', $post['role']);
            }

            //Calculate next availability
            foreach ($list as $key => $value) {
                //Get user availability
                $userAvailDates = [];
                $userAvailabilityDates = $this->userAvailability->where('user_id', $value->id)
                    ->where('date', '>=', date('Y-m-d'))->where('type', '!=', 'appraisal')->get();
                foreach ($userAvailabilityDates as $avilDate) {
                    $userAvailDates[] = $avilDate->date;
                }
                $getAvailableDates = findMissedDates($userAvailDates);
                $value->nex_available_date = $getAvailableDates['first_missed_date'];

                if ($fromDate && $toDate) {
                    if ($value->nex_available_date >= $fromDate && $value->nex_available_date <= $toDate) {
                        array_push($listArray, $value);
                    }
                } else {
                    $listArray[$key] = $value;
                }
            }

            //Create pagination if user search next availability
            if ($fromDate && $toDate) {
                // Get current page form url e.x. &amp;page=1
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                // Create a new Laravel collection from the array data
                $itemCollection = collect($listArray);
                // Define how many items we want to be visible in each page
                $perPage = 10;
                // Slice the collection to get the items to display in current page
                $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
                // Create our paginator and pass it to the view
                $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);
                // set url path for generted links
                $paginatedItems->setPath($request->url());
                $list = $paginatedItems;
            }

            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Get user Detail By Id
    */
    public function getUserInfoById($user_id)
    {
        try {
            return $this->user->where(['id' => $user_id])->select('id', 'name', 'email', 'online_offline', 'profile_image', 'phone_number_country_code', 'phone_number', 'status', 'user_role', 'user_type', 'is_visibility')->first();
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
    /*
    * Get user Detail By Id
    */
    public static function getUserInfo($user_id)
    {
        try {
            return User::where(['id' => $user_id])->select('id', 'name', 'email', 'online_offline', 'profile_image', 'phone_number_country_code', 'phone_number', 'status', 'user_role', 'user_type', 'is_visibility')->first();
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
            $userType = 'User';
            if(!empty($request->user_type)){
                $userType = ucfirst($request->user_type);
            }
            $userData = User::where(['id' => $request->id])->select('id', 'name', 'email','status')->first();
            if(!empty($userData)){
                $userData->update(array('status' => 'deleted'));
                $message = $userType.' successfully deleted.';
                $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            }else{
                $response = ['success' => false, 'message' => 'User does not found.', 'error' => [], 'data' => []];
            }
            return $response;
        } catch (\Exception $e) {
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
            $userData = User::where(['id' => $request->id])->select('id', 'name', 'email','status')->first();
            if(!empty($userData)){
                $userData->update(array('status' => $request->status));
                $message ='Status successfully changed.';
                $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            }else{
                $response = ['success' => false, 'message' => 'User does not found.', 'error' => [], 'data' => []];
            }
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

     /**
     * To get user strength
     * @param
     * @return Array
     */
    public static function getCompanyStrength() {
        try {
            $userData = Auth::user();
            $data = array('totalEmployeesCount'=>0,'totalManagerCount'=>0);
            if(!empty($userData->companyDetails)){
                 $data['totalEmployeesCount'] = User::where('status','!=','deleted')->where('employees.company_id', $userData->companyDetails->id)->join('employees', 'users.id', '=', 'employees.user_id')->count();
                 $data['totalManagerCount'] = User::where('status','!=','deleted')->where('managers.company_id', $userData->companyDetails->id)->join('managers', 'users.id', '=', 'managers.user_id')->count();
            }

            return $data;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get company stats count
     * @param
     * @return Array
     */
    public static function getCompanyStatsDetail() {
        try {
            $userData = Auth::user();
            $data = array('totalEmployeesCount'=>0,'totalManagersCount'=>0, 'totalContractsCount'=>0, 'totalEarning'=>0);
            if(!empty($userData->companyDetails)){
                $data['totalEmployeesCount'] = User::where('status','!=','deleted')->where('employees.company_id', $userData->companyDetails->id)->join('employees', 'users.id', '=', 'employees.user_id')->count();
                $data['totalManagersCount'] =  User::where('status','!=','deleted')->where('managers.company_id', $userData->companyDetails->id)->join('managers', 'users.id', '=', 'managers.user_id')->count();
                $data['totalContractsCount'] = Job::where('jobs.status','!=','deleted')->join('job_assignments', 'jobs.id', '=', 'job_assignments.job_id')->where('jobs.job_state', 'completed')->where('job_assignments.assigned_to', $userData->id)->where('job_assignments.status', 'active')->count();
                $totalEarning = DB::select("Select IFNULL(SUM(jb.price),0) total_earning from jobs AS j
                                                    Join job_assignments AS ja ON ja.job_id=j.id
                                                    Join job_bids AS jb ON jb.id=ja.job_bid_id
                                                    where j.status!='deleted' AND j.job_state='completed' AND ja.status='active' AND ja.assigned_to=".$userData->id);
                if(!empty($totalEarning) && count($totalEarning)){
                    $data['totalEarning'] = $totalEarning[0]->total_earning;
                }
           }
            return $data;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }


    public function login_store($request)
    {   
        try {

            $remember_me = $request->has('remember_me') ? true : false;
            
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'user_type' => 'store', 'status' => 'active'], $remember_me)) {
                    
                    if ($remember_me) {
                        setcookie('email', $request->email, time() + (365 * 24 * 60 * 60));
                        setcookie("password", $request->password, time() + (365 * 24 * 60 * 60));
                    } else {
                        if (isset($_COOKIE["email"])) {
                            setcookie("email", "");
                        }
                        if (isset($_COOKIE["password"])) {
                            setcookie("password", "");
                        }
                    }

                    return ['success' => true, 'message' => 'Login successfully.', 'error' => [], 'data' => []];
                }
           
            return ['success' => false, 'message' => 'Invalid credential.', 'error' => [], 'data' => []];
        } catch (\Exception $ex) {
            return ['success' => false, 'message' => $ex->getMessage(), 'error' => [], 'data' => []];
        }
    }


    public function socialLink($request){
        try{
            $query = socialLink::query();
            if($request->segment(1)=='admin'){

                $links= $query->whereNull('store_id')->first();
            }else{

                $links= $query->where(['store_id'=> Auth::user()->store_id])->first();
            }

            return $links;
        }
        catch(Exception $e){
            return ['success' => false];
        }
    }

    public function updateSocialLinks($request){
        try{
            $links = socialLink::updateOrCreate(['id' => $request->social_id], $request->all());
            return ['success' => true, 'message' => "Social Links updated successfully"];
        }
        catch(Exception $e){
            return ['success' => false, 'message' => "Something went wrong"];
        }
    }
}
