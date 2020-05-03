<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddStoreMasterRequest;
use Modules\Admin\Http\Requests\EditStoreMasterRequest;
use App\Repositories\StoreMasterRepository;
use App\Models\StoreMaster;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\Cities;
use File;

use View;

class StoreMasterController extends Controller
{
    private $StoreMasterRepository;

    public function __construct(StoreMasterRepository $StoreMasterRepository)
    {
        $this->StoreMasterRepository = $StoreMasterRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::store-master.index');
    }
    public function getStoreMasterList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->StoreMasterRepository->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }
    public function addStoreMaster()
    {
        $adminDetail = Auth::user();
        $country = Country::get();
        return view('admin::store-master.add', ['adminDetail' => $adminDetail, 'country' => $country]);
    }

    public function getStateList(Request $request)
    {  
        $states = State::where('country_id', $request->id)->get();
        $html = '<option value="">Please select</option>';
        foreach ($states as $key => $value) {
            $html .= '<option value="'.$value->id.'">'.$value->name.'</option>';         
        }
        return $html;
    }

    public function getCityList(Request $request)
    {  
        $cities = Cities::where('state_id', $request->id)->get();
        $html = '<option value="">Please select</option>';
        foreach ($cities as $key => $value) {
            $html .= '<option value="'.$value->id.'">'.$value->name.'</option>';         
        }
        return $html;
    }



    public function getEditStoreMasterDetails($id)
    {
        try {

            $data = $this->StoreMasterRepository->getDetail($id);
            $country = Country::get();
            $state = State::where('country_id', $data['store_data']->country_id)->get();
            $city = Cities::where('state_id', $data['store_data']->state_id)->get();
            return view('admin::store-master.edit', ['detail' => $data['store_data'], 'country' => $country, 'state' => $state, 'city' => $city,  'user_id'=> isset($data['user_data']->id)?$data['user_data']->id:'']);

        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function saveStoreMaster(AddStoreMasterRequest $request)
    {

        try {
            $email = User::where('email', $request->email)->where('status','!=',  'deleted')->first();
            if(!empty($email)){
                return Response::json(['success' => false, 'message' => 'Email already exist']);
            }
            $result = $this->StoreMasterRepository->create($request);
            return $result;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function updateStoreMaster(EditStoreMasterRequest $request)
    {
        try {
            return $data = $this->StoreMasterRepository->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteStoreMaster(Request $request)
    {
        try {
            return $this->StoreMasterRepository->delete($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getStoreMasterDetails($id)
    {
        try {
            $data = $this->StoreMasterRepository->getDetail($id);
            return View::make('admin::store-master.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewStoreMaster($id)
    {
        $user = StoreMaster::where('id', $id)->first();
        return view('admin::store-master.view', ['detail' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->StoreMasterRepository->changeStatus($request);
    }

    public function getStoreGST(Request $request)
    {
        $data = $this->StoreMasterRepository->storeGST($request);
        return view('Store::store-master.edit_gst', compact('data'));
    }
    public function updateStoreGST(Request $request)
    {
        $status = $this->user->updateStoreGST($request);
        if($status['success'] == 1){    
            Session::flash('message', $status['message']);
        }else{
            Session::flash('message', $status['message']);
        }
        return redirect('admin/manage-social/edit');
    }
}
