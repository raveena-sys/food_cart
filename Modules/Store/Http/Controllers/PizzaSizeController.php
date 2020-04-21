<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddPizzaCheeseRequest;
use Modules\Admin\Http\Requests\AddPizzaSizeRequest;
use Modules\Admin\Http\Requests\EditPizzaSizeRequest;
use App\Repositories\PizzaSizeRepository;
use App\Models\PizzaSizeMaster;
use App\Models\StorePizzaSize;
use App\Models\PizzaExtraCheese;

use View;

class PizzaSizeController extends Controller
{
    private $PizzaSizeRepository;

    public function __construct(PizzaSizeRepository $PizzaSizeRepository)
    {
        $this->PizzaSizeRepository = $PizzaSizeRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('store::manage-master.pizza-size.index');
    }
    public function getPizzaSizeList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->PizzaSizeRepository->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function addPizzaSize(AddPizzaSizeRequest $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->PizzaSizeRepository->create($request);
                return $result;
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function updatePizzaSize(EditPizzaSizeRequest $request)
    {
        try {
            return $data = $this->PizzaSizeRepository->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deletePizzaSize(Request $request)
    {
        try {
            return $this->PizzaSizeRepository->delete($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getPizzaSizeDetails($id)
    {
        try {
            $data = $this->PizzaSizeRepository->getDetail($id);
            return View::make('store::manage-master.pizza-size.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewPizzaSize($id)
    {
        $user = $this->PizzaSizeRepository->getDetail($id);
        return view('store::manage-master.pizza-size.view', ['category' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->PizzaSizeRepository->changeStatus($request);
    }


    public function getCustomSize(Request $request){
      
        $products = PizzaSizeMaster::select("id", "name", "price")->where(['status' => 'active', 'store_id' => Null]);

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
        $data['size'] = $products->get();
        $data['selected_ids'] ='';
        return view('store::manage-master.pizza-size.sizeselection')->with($data);
    }


    
    public function addselection(Request $request){

        try {
            $result = $this->PizzaSizeRepository->storeSizeSelection($request);
            return $result;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }



    /**
     * Display a listing of the Pizza cheese module.
     * @return Response
     */
    public function pizzaCheese()
    {
        return view('store::manage-master.pizza-cheese.index');
    }
    public function getPizzaCheeseList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->PizzaSizeRepository->getPizzaCheeseList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function addPizzaCheese(AddPizzaCheeseRequest $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->PizzaSizeRepository->createPizzaCheese($request);
                return $result;
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function updatePizzaCheese(AddPizzaCheeseRequest $request)
    {
        try {
            return $data = $this->PizzaSizeRepository->updatePizzaCheese($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deletePizzaCheese(Request $request)
    {
        try {
            return $this->PizzaSizeRepository->deletePizzaCheese($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getPizzaCheeseDetails($id)
    {
        try {
            /*$data =*/ return $this->PizzaSizeRepository->getPizzaCheeseDetail($id);
            //return View::make('admin::manage-master.pizza-cheese.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewPizzaCheese($id)
    {
        $user = $this->PizzaSizeRepository->getPizzaCheeseDetail($id);
        return view('store::manage-master.pizza-cheese.view', ['category' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changePizzaCheeseStatus(Request $request)
    {
        return $this->PizzaSizeRepository->changePizzaCheeseStatus($request);
    }

    public function getCustomCheese(Request $request){
      
        $products = PizzaExtraCheese::select("pizza_size_master.name","pizza_extra_cheese.id", "pizza_size_master.size_master_id", "pizza_extra_cheese.price")->where(['pizza_extra_cheese.status' => 'active', 'pizza_extra_cheese.store_id' => Null])->join('pizza_size_master', 'pizza_size_master.id','=', 'pizza_extra_cheese.pizza_size_master');

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
        $data['cheese'] = $products->get();
        $data['selected_ids'] ='';
        return view('store::manage-master.pizza-cheese.cheeseselection')->with($data);
    }


    
    public function addselectioncheese(Request $request){

        try {
            $result = $this->PizzaSizeRepository->storeCheeseSelection($request);
            return $result;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
