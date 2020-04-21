<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddDrinkMasterRequest;
use Modules\Admin\Http\Requests\EditDrinkMasterRequest;
use App\Repositories\DrinkMasterRepository;
use App\Models\DrinkMaster;

use View;

class DrinkMasterController extends Controller
{
    private $DrinkMasterRepository;

    public function __construct(DrinkMasterRepository $DrinkMasterRepository)
    {
        $this->DrinkMasterRepository = $DrinkMasterRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::manage-master.drink-master.index');
    }
    public function getDrinkMasterList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->DrinkMasterRepository->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function addDrinkMaster(AddDrinkMasterRequest $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->DrinkMasterRepository->create($request);
                return $result;
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function updateDrinkMaster(EditDrinkMasterRequest $request)
    {
        try {
            return $data = $this->DrinkMasterRepository->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteDrinkMaster(Request $request)
    {
        try {
            return $this->DrinkMasterRepository->delete($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getDrinkMasterDetails($id)
    {
        try {
            $data = $this->DrinkMasterRepository->getDetail($id);
            return View::make('admin::manage-master.drink-master.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewDrinkMaster($id)
    {
        $user = DrinkMaster::where('id', $id)->first();
        return view('admin::manage-master.drink-master.view', ['category' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->DrinkMasterRepository->changeStatus($request);
    }
}
