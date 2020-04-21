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

    
}
