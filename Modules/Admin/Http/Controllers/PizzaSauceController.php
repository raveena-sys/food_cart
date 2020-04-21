<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddPizzaSauceRequest;
use Modules\Admin\Http\Requests\EditPizzaSauceRequest;
use App\Repositories\PizzaSauceRepository;
use App\Models\PizzaSauceMaster;

use View;

class PizzaSauceController extends Controller
{
    private $PizzaSauceRepository;

    public function __construct(PizzaSauceRepository $PizzaSauceRepository)
    {
        $this->PizzaSauceRepository = $PizzaSauceRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::manage-master.pizza-sauce.index');
    }
    public function getPizzaSauceList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->PizzaSauceRepository->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function addPizzaSauce(AddPizzaSauceRequest $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->PizzaSauceRepository->create($request);
                return $result;
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function updatePizzaSauce(EditPizzaSauceRequest $request)
    {
        try {
            return $data = $this->PizzaSauceRepository->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deletePizzaSauce(Request $request)
    {
        try {
            return $this->PizzaSauceRepository->delete($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getPizzaSauceDetails($id)
    {
        try {
            $data = $this->PizzaSauceRepository->getDetail($id);
            return View::make('admin::manage-master.pizza-sauce.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewPizzaSauce($id)
    {
        $user = PizzaSauceMaster::where('id', $id)->first();
        return view('admin::manage-master.pizza-sauce.view', ['category' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->PizzaSauceRepository->changeStatus($request);
    }
}
