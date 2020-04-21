<?php

namespace Modules\Admin\Http\Controllers;

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
        return view('admin::manage-master.topping-dips.index');
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
            return View::make('admin::manage-master.topping-dips.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewToppingDips($id)
    {
        $user = ToppingDips::where('id', $id)->first();
        return view('admin::manage-master.topping-dips.view', ['category' => $user]);
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
}
