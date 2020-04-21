<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\EditPizzaSizeRequest;
use Modules\Admin\Http\Requests\AddPizzaSizeRequest;
use App\Repositories\PizzaSizeRepository;
use App\Models\PizzaSizeMaster;
use App\Models\PizzaExtraCheese;
use Modules\Admin\Http\Requests\AddPizzaCheeseRequest;
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
        return view('admin::manage-master.pizza-size.index');
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
            return View::make('admin::manage-master.pizza-size.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewPizzaSize($id)
    {
        $user = PizzaSizeMaster::where('id', $id)->first();
        return view('admin::manage-master.pizza-size.view', ['category' => $user]);
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






    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function pizzaCheese()
    {
        return view('admin::manage-master.pizza-cheese.index');
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
        $user = PizzaExtraCheese::where('id', $id)->first();
        return view('admin::manage-master.pizza-cheese.view', ['category' => $user]);
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



}
