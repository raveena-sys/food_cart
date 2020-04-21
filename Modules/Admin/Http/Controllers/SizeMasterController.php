<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddSizeMasterRequest;
use Modules\Admin\Http\Requests\EditSizeMasterRequest;
use App\Repositories\SizeMasterRepository;
use App\Models\SizeMaster;

use View;

class SizeMasterController extends Controller
{
    private $SizeMasterRepository;

    public function __construct(SizeMasterRepository $SizeMasterRepository)
    {
        $this->SizeMasterRepository = $SizeMasterRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::manage-master.size-master.index');
    }
    public function getSizeMasterList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->SizeMasterRepository->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function addSizeMaster(AddSizeMasterRequest $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->SizeMasterRepository->create($request);
                return $result;
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }


    public function updateSizeMaster(EditSizeMasterRequest $request)
    {
        try {
            return $data = $this->SizeMasterRepository->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteSizeMaster(Request $request)
    {
        try {
            return $this->SizeMasterRepository->delete($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function getSizeMasterDetails($id)
    {
        try {
            $data = $this->SizeMasterRepository->getDetail($id);
            return View::make('admin::manage-master.size-master.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewSizeMaster($id)
    {
        $user = SizeMaster::where('id', $id)->first();
        return view('admin::manage-master.size-master.view', ['category' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->SizeMasterRepository->changeStatus($request);
    }
}
