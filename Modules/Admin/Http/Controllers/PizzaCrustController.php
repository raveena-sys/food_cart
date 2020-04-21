<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddPizzaCrustRequest;
use Modules\Admin\Http\Requests\EditPizzaCrustRequest;
use App\Repositories\PizzaCrustRepository;
use App\Models\PizzaCrustMaster;

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
        return view('admin::manage-master.pizza-crust.index');
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
            return View::make('admin::manage-master.pizza-crust.pizzacrust-edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewPizzaCrust($id)
    {
        $user = PizzaCrustMaster::where('id', $id)->first();
        return view('admin::manage-master.pizza-crust.view', ['category' => $user]);
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
}
