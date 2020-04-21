<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddToppingDipsRequest;
use Modules\Admin\Http\Requests\EditToppingDipsRequest;
use App\Repositories\ToppingDipsRepository;
use App\Models\ToppingDips;

use View;

class ToppingDipsController extends Controller
{
    private $ToppingDipsRepository;

    public function __construct(ToppingDipsRepository $ToppingDipsRepository)
    {
        $this->ToppingDipsRepository = $ToppingDipsRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('store::manage-master.topping-dips.index');
    }
    public function getToppingDipsList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->ToppingDipsRepository->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function addToppingDips(AddToppingDipsRequest $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->ToppingDipsRepository->create($request);
                return $result;
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function updateToppingDips(EditToppingDipsRequest $request)
    {
        try {
            return $data = $this->ToppingDipsRepository->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteToppingDips(Request $request)
    {
        try {
            return $this->ToppingDipsRepository->delete($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getToppingDipsDetails($id)
    {
        try {
            $data = $this->ToppingDipsRepository->getDetail($id);
            return View::make('store::manage-master.topping-dips.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewToppingDips($id)
    {
        $user = $this->ToppingDipsRepository->getDetail($id);
        return view('store::manage-master.topping-dips.view', ['category' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->ToppingDipsRepository->changeStatus($request);
    }


    public function getCustomTopDips(Request $request){
      
        $products = ToppingDips::select("id", "name", "price")->where(['status' => 'active', 'store_id' => Null]);

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
        $data['top'] = $products->get();
        $data['selected_ids'] ='';
        return view('store::manage-master.topping-dips.toppingdipselection')->with($data);
    }


    
    public function addselection(Request $request){

        try {
            $result = $this->ToppingDipsRepository->storeDipSelection($request);
            return $result;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
