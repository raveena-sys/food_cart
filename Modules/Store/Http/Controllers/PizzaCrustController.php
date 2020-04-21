<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddPizzaCrustRequest;
use Modules\Admin\Http\Requests\EditPizzaCrustRequest;
use App\Repositories\PizzaCrustRepository;
use App\Models\PizzaCrustMaster;
use App\Models\StorePizzaCrust;

use View;

class PizzaCrustController extends Controller
{
    private $pizzaCrustRepository;

    public function __construct(PizzaCrustRepository $PizzaCrustRepository)
    {
        $this->pizzaCrustRepository = $PizzaCrustRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('store::manage-master.pizza-crust.index');
    }
    public function getPizzaCrustList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->pizzaCrustRepository->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function addPizzaCrust(AddPizzaCrustRequest $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->pizzaCrustRepository->create($request);
                return $result;
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }


    public function updatePizzaCrust(EditPizzaCrustRequest $request)
    {
        try {
            return $data = $this->pizzaCrustRepository->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deletePizzaCrust(Request $request)
    {
        try {
            return $this->pizzaCrustRepository->delete($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function getPizzaCrustDetails($id)
    {
        try {
            $data = $this->pizzaCrustRepository->getDetail($id);

            return View::make('store::manage-master.pizza-crust.pizzacrust-edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewPizzaCrust($id)
    {
        $user = $this->pizzaCrustRepository->getDetail($id);
        return view('store::manage-master.pizza-crust.view', ['category' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->pizzaCrustRepository->changeStatus($request);
    }


    public function getCustomCrust(Request $request){
    

        $products = PizzaCrustMaster::select("id", "name", "price")->where(['status' => 'active', 'store_id' => Null]);

        if($request->id){
            $idArray = explode(',', $request->id);
            $products->whereNotIN("id", $idArray);
        }
        /*if($request->product_id){
            $products->where("product.id", $request->product_id);
            $data['products'] = $products->get();
            $data['selected_ids'] = $request->product_id;
            return view('store::product.productselection')->with($data);
        }*/
        $data['crusts'] = $products->get();
        $data['selected_ids'] ='';
        return view('store::manage-master.pizza-crust.crustselection')->with($data);
    }


    
    public function addselection(Request $request){

        try {
            $result = $this->pizzaCrustRepository->storeCrustSelection($request);
            return $result;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
