<?php

namespace Modules\Admin\Http\Controllers;

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
        return view('admin::manage-master.topping-donair-shawarma-mediterranean.index');
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
            return View::make('admin::manage-master.topping-donair-shawarma-mediterranean.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewToppingDonairShawarmaMediterranean($id)
    {
        $user = ToppingDonairShawarmaMediterranean::where('id', $id)->first();
        return view('admin::manage-master.topping-donair-shawarma-mediterranean.view', ['category' => $user]);
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
}
