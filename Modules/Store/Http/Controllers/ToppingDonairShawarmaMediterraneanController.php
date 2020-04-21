<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddToppingDonairShawarmaMediterraneanRequest;
use Modules\Admin\Http\Requests\EditToppingDonairShawarmaMediterraneanRequest;
use App\Repositories\ToppingDonairShawarmaMediterraneanRepository;
use App\Models\ToppingDonairShawarmaMediterranean;

use View;

class ToppingDonairShawarmaMediterraneanController extends Controller
{
    private $ToppingDonairShawarmaMediterraneanRepository;

    public function __construct(ToppingDonairShawarmaMediterraneanRepository $ToppingDonairShawarmaMediterraneanRepository)
    {
        $this->ToppingDonairShawarmaMediterraneanRepository = $ToppingDonairShawarmaMediterraneanRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('store::manage-master.topping-donair-shawarma-mediterranean.index');
    }
    public function getToppingDonairShawarmaMediterraneanList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->ToppingDonairShawarmaMediterraneanRepository->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function addToppingDonairShawarmaMediterranean(AddToppingDonairShawarmaMediterraneanRequest $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->ToppingDonairShawarmaMediterraneanRepository->create($request);
                return $result;
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function updateToppingDonairShawarmaMediterranean(EditToppingDonairShawarmaMediterraneanRequest $request)
    {
        try {
            return $data = $this->ToppingDonairShawarmaMediterraneanRepository->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteToppingDonairShawarmaMediterranean(Request $request)
    {
        try {
            return $this->ToppingDonairShawarmaMediterraneanRepository->delete($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getToppingDonairShawarmaMediterraneanDetails($id)
    {
        try {
            $data = $this->ToppingDonairShawarmaMediterraneanRepository->getDetail($id);
            return View::make('store::manage-master.topping-donair-shawarma-mediterranean.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewToppingDonairShawarmaMediterranean($id)
    {
        $user = $this->ToppingDonairShawarmaMediterraneanRepository->getDetail($id);
        return view('store::manage-master.topping-donair-shawarma-mediterranean.view', ['category' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->ToppingDonairShawarmaMediterraneanRepository->changeStatus($request);
    }
    public function getCustomTopDon(Request $request){
      
        $products = ToppingDonairShawarmaMediterranean::select("id", "name", "price")->where(['status' => 'active', 'store_id' => Null]);

        if($request->id){
            $idArray = explode(',', $request->id);
            $products->whereNotIN("id", $idArray);
        }
        $data['top_don'] = $products->get();
        $data['selected_ids'] ='';
        return view('store::manage-master.topping-donair-shawarma-mediterranean.toppingdonselection')->with($data);
    }


    
    public function addselection(Request $request){

        try {
            $result = $this->ToppingDonairShawarmaMediterraneanRepository->storeToppingDonairSelection($request);
            return $result;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
