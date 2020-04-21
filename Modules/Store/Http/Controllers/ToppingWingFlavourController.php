<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddToppingWingFlavourRequest;
use Modules\Admin\Http\Requests\EditToppingWingFlavourRequest;
use App\Repositories\ToppingWingFlavourRepository;
use App\Models\ToppingWingFlavour;

use View;

class ToppingWingFlavourController extends Controller
{
    private $ToppingWingFlavourRepository;

    public function __construct(ToppingWingFlavourRepository $ToppingWingFlavourRepository)
    {
        $this->ToppingWingFlavourRepository = $ToppingWingFlavourRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('store::manage-master.topping-wing-flavour.index');
    }
    public function getToppingWingFlavourList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->ToppingWingFlavourRepository->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function addToppingWingFlavour(AddToppingWingFlavourRequest $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->ToppingWingFlavourRepository->create($request);
                return $result;
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function updateToppingWingFlavour(EditToppingWingFlavourRequest $request)
    {
        try {
            return $data = $this->ToppingWingFlavourRepository->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteToppingWingFlavour(Request $request)
    {
        try {
            return $this->ToppingWingFlavourRepository->delete($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getToppingWingFlavourDetails($id)
    {
        try {
            $data = $this->ToppingWingFlavourRepository->getDetail($id);
            return View::make('store::manage-master.topping-wing-flavour.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewToppingWingFlavour($id)
    {

        $user = $this->ToppingWingFlavourRepository->getDetail($id);
        return view('store::manage-master.topping-wing-flavour.view', ['category' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->ToppingWingFlavourRepository->changeStatus($request);
    }

    public function getCustomTopWing(Request $request){
      
        $products = ToppingWingFlavour::select("id", "name", "price")->where(['status' => 'active', 'store_id' => Null]);

        if($request->id){
            $idArray = explode(',', $request->id);
            $products->whereNotIN("id", $idArray);
        }
        $data['top_wing'] = $products->get();
        $data['selected_ids'] ='';
        return view('store::manage-master.topping-wing-flavour.toppingwingselection')->with($data);
    }


    
    public function addselection(Request $request){

        try {
            $result = $this->ToppingWingFlavourRepository->storeToppingWingsSelection($request);
            return $result;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
