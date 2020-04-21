<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB;

class MasterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::master.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function topping(Request $request)
    {
        return view('admin::topping_master');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function getData(Request $request)
    {

        $thumb_image = $request->file('thumb_img');
        $imageName = $request->file('image');
        if ($thumb_image != "") {

            $thumb_image = time() . '.' . request()->thumb_img->getClientOriginalExtension();
            request()->thumb_img->move(public_path('images'), $thumb_image);
        }

        if ($imageName != "") {

            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images'), $imageName);
        }
        // echo $imageName."and".$thumb_image ."and".$status;
        // //print_r($request);
        // die;
        $food_type_master_id = $request->input('foodtype');
        $name = $request->input('name');
        $price = $request->input('price');
        $status = $request->input('status');

        $data = array('food_type_master_id' => $food_type_master_id, "name" => $name, "price" => $price, 'thumb_image' => $thumb_image, "image" => $imageName, "status" => $status, "created_by" => date('Y-m-d H:i:s'), "updated_by" => date('Y-m-d H:i:s'));
        DB::table('topping_master')->insert($data);
        // echo "Record inserted successfully.<br/>";
        // echo '<a href = "insert">Click Here</a> to go back.';
        return redirect('admin/topping_master')->with('success', 'Topping detail inserted successfully.');
    }
}
