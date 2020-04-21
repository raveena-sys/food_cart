<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddToppingPizzaRequest;
use Modules\Admin\Http\Requests\EditToppingPizzaRequest;
use App\Repositories\ToppingPizzaRepository;
use App\Models\ToppingPizza;

use View;

class ToppingPizzaController extends Controller
{
    private $ToppingPizzaRepository;

    public function __construct(ToppingPizzaRepository $ToppingPizzaRepository)
    {
        $this->ToppingPizzaRepository = $ToppingPizzaRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::manage-master.topping-pizza.index');
    }
    public function getToppingPizzaList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->ToppingPizzaRepository->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function addToppingPizza(AddToppingPizzaRequest $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->ToppingPizzaRepository->create($request);
                return $result;
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function updateToppingPizza(EditToppingPizzaRequest $request)
    {
        try {
            return $data = $this->ToppingPizzaRepository->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteToppingPizza(Request $request)
    {
        try {
            return $this->ToppingPizzaRepository->delete($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getToppingPizzaDetails($id)
    {
        try {
            $data = $this->ToppingPizzaRepository->getDetail($id);
            return View::make('admin::manage-master.topping-pizza.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewToppingPizza($id)
    {
        $user = ToppingPizza::where('id', $id)->first();
        return view('admin::manage-master.topping-pizza.view', ['category' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->ToppingPizzaRepository->changeStatus($request);
    }
}
