<?php

namespace App\Repositories;


use App\Models\Cms;
use DataTables;
use Illuminate\Support\Str;
use File;

class CmsRepository {

    public function __construct(Cms $cms) {
        $this->cms = $cms;

    }

    public function getAllCms($request) {
        return $cms = Cms::all();
    }

    public function showEditCms($id) {
        return Cms::where(['id' => $id])->first();
    }

    public function updateCmsPage($request) {

        try {
            $model =  $this->cms->where('id',$request['cms_id'])->first();

            $fileName = "";
            $profilePath = public_path() . '/uploads/cms';

            if (!is_dir($profilePath)) {
                File::makeDirectory($profilePath, $mode = 0777, true, true);
            }
            if ($request->hasFile('header_image')) {
                $file = $request->file('header_image');
                $fileName = $file->getClientOriginalName();
                $fileExtension = strtolower($file->getClientOriginalExtension());
                $name = time().'.'.$fileExtension;
                $imageExist = public_path() . '/uploads/cms/' . $name;
                $request->file('header_image')->move($profilePath, $name);

                $model->header_image = $name;
            }

            if (!is_dir($profilePath)) {
                File::makeDirectory($profilePath, $mode = 0777, true, true);
            }
            if ($request->hasFile('background_image')) {
                $file = $request->file('background_image');
                $fileName = $file->getClientOriginalName();
                $fileExtension = strtolower($file->getClientOriginalExtension());
                $name = time().'.'.$fileExtension;
                $imageExist = public_path() . '/uploads/cms/' . $name;
                $request->file('background_image')->move($profilePath, $name);

                $model->background_image = $name;
            }
            if (!is_dir($profilePath)) {
                File::makeDirectory($profilePath, $mode = 0777, true, true);
            }
            if ($request->hasFile('side_image')) {
                $file = $request->file('side_image');
                $fileName = $file->getClientOriginalName();
                $fileExtension = strtolower($file->getClientOriginalExtension());
                $name = time().'.'.$fileExtension;
                $imageExist = public_path() . '/uploads/cms/' . $name;
                $request->file('side_image')->move($profilePath, $name);

                $model->side_image = $name;
            }

            $model->page_title = $request->page_title;
            $model->page_content = $request->page_content;
            $model->left_content = $request->left_content;
            $model->right_content = $request->right_content;
            $model->save();

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' =>$model];
            return $response;
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function faqlist($request) {
        try {
            $faq = $this->faq->latest()->get();
            $tableResult = Datatables::of($faq)
                    ->addIndexColumn()
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->question)) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return Str::contains(Str::lower($row['question']), Str::lower(trim($request->question))) ? true : false;
                            });
                        }
                        if (!empty($request->status)) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return Str::contains(Str::lower($row['status']), Str::lower(trim($request->status))) ? true : false;
                            });
                        }
                    })
                    ->editColumn('answer', function ($faq) {
                        return strip_tags($faq->answer);
                    })
                    ->addColumn('status', function($row) {
                        $row_status = isset($row->status) ? $row->status : "";
                        $ischecked = ($row_status == 'active') ? "checked" : "";
                        $btn = "<div class='switch'> <label> <input class='onoffswitch-checkbox switchchange' name='onoffswitch' type='checkbox' data-id='$row->id'  data-url='" . url('admin/faq-status') . "' data-status='$row_status' id='myonoffswitch$row->id' $ischecked onclick='Change_Status($row->id)' data-tableid='faq-list'> <span class='lever'></span> </label> </div>";
                        return $btn;
                    })
                    ->addColumn('action', function($row) {
                        $type = 'faq';
                        $btn = "<ul class='list-inline action'>
                                    <li class='list-inline-item'>
                                        <a href='" . url('admin/faq-detail/' . $row->id) . "' ><i class='fa fa-eye mr-1'></i></a>
                                    </li>
                                    <li class='list-inline-item'>
                                        <a href='" . url('admin/faq-edit/' . $row->id) . "' ><i class='iconmoon-pencil mr-1'></i></a></li> <li class='list-inline-item'>
                                        <a href='javascript:;' id='remove$row->id' onclick='deleteModal($row->id)' data-url='" . url('admin/delete-faq') . "' data-tableid='faq-list' data-types='$type' data-name='$row->property_name'><i class='iconmoon-trash mr-1' ></i></a>
                                    </li>
                                </ul>";
                        return $btn;
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
            return $response;
        } catch (\Exception $e) {

            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function faqStatus($id, $mode) {
        try {
            $this->faq->where('id', $id)->update(['status' => $mode]);
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function deleteFaq($id) {
        try {
            $faq = $this->faq->where('id', $id)->delete();
            $response = ['success' => true, 'message' => 'Faq deleted successfully', 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function detail($id) {
        return $this->faq->where('id', $id)->first();
    }

    public function addfaq($post) {
        try {
            if (isset($post['id']) && !empty($post['id'])) {
                $faq = $this->faq->where('id', $post['id'])->first();
                $faq->question = $post['question'];
                $faq->answer = $post['answer'];
                $faq->save();
                $response = ['success' => true, 'message' => 'Faq Updated successfully', 'error' => [], 'data' => []];
                return $response;
            } else {
                $response = $this->faq->create($post);
                $response = ['success' => true, 'message' => 'Faq added successfully', 'error' => [], 'data' => []];
                return $response;
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

}
