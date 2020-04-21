<?php

namespace Modules\Admin\Http\Controllers;

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
        return view('admin::manage-master.topping-wing-flavour.index');
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
            return View::make('admin::manage-master.topping-wing-flavour.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewToppingWingFlavour($id)
    {

        $user = ToppingWingFlavour::where('id', $id)->first();
        return view('admin::manage-master.topping-wing-flavour.view', ['category' => $user]);
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
}
