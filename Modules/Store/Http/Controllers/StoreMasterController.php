<?php

namespace Modules\Store\Http\Controllers;

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
use Session;
use View;

class StoreMasterController extends Controller
{
    private $StoreMasterRepository;

    public function __construct(StoreMasterRepository $StoreMasterRepository)
    {
        $this->StoreMasterRepository = $StoreMasterRepository;
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
            return view('store::store-master.edit', ['detail' => $data['store_data'], 'country' => $country, 'state' => $state, 'city' => $city,  'user_id'=> isset($data['user_data']->id)?$data['user_data']->id:'']);

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


    public function getStoreGST(){
        $data = $this->StoreMasterRepository->getStoreGST();
        return view('store::store-master.editGST', compact('data'));
    }


    public function updateStoreGST(Request $request)
    {
        $status = $this->StoreMasterRepository->updataStoreGST($request);
        Session::flash('message', $status['message']);
        return redirect('store/manage-gst/edit');
    }

    public function delivery_zone(){
        //$data = $this->StoreMasterRepository->getStoreGST();
        return view('store::manage-delivery.index');
    }

    public function delivery_zone_add(){
        //$data = $this->StoreMasterRepository->getStoreGST();
        return view('store::manage-delivery.add_delivery_zone');
    }

    public function delivery_zone_list(Request $request){
        
        try {
                $result = $this->StoreMasterRepository->delivery_zone_list($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
    }
    public function delivery_zone_addpost(Request $request){
        if(!empty($request->zip_code) && count($request->zip_code)>1){

            $data = $this->StoreMasterRepository->delivery_zone_add($request);
            if($data['success'] ==1){
                Session::flash('message', $data['message']);

            }else{
                Session::flash('message', $data['message']);
            }
            return redirect('store/manage-delivery/');
        }else{
            Session::flash('errmessage', 'Please enter postal code');
            return redirect()->back();
        }
    }


    

    public function delivery_zone_detail($id)
    {
        try {
            $data = $this->StoreMasterRepository->getDeliveryDetail($id);

            return view('store::manage-delivery.add_delivery_zone', compact('data'));
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }



    public function delivery_zone_status(Request $request)
    {
        return $this->StoreMasterRepository->delivery_zone_status($request);
    }

}
