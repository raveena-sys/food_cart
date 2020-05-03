<?php

namespace App\Repositories;

use App\EmailQueue\CreateEmployee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use Auth;
use Datatables;
use App\Common\Helpers;
use App\Models\Job;
use App\Models\StorePostalCode;
use App\Models\StoreMaster;
use \DB, Hash, Mail;

/**
 * Class EmployeeRepository.
 */

class StoreMasterRepository
{
    private $storeMaster, $user;

    public function __construct(
        User $user,
        StoreMaster $storeMaster
    ) {
        $this->user = $user;
        $this->storeMaster = $storeMaster;
    }

    public function getList($request)
    {
        try {
            
            $storeMaster = $this->storeMaster->where('status', '!=', 'deleted');
            if (!empty($request->status)) {
                $storeMaster->where('status', $request->status);
            }

            $storeMaster = $storeMaster->latest()->get();
            $tableResult = Datatables::of($storeMaster)->addIndexColumn()

                ->filter(function ($instance) use ($request) {

                    if (!empty($request->name)) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(Str::lower($row['name']), Str::lower(trim($request->name))) ? true : false;
                        });
                    }
                })
                ->editColumn('name', function ($data) {
                    $userName = $data->name;
                    return $userName;       
                })
                ->editColumn('address1', function ($data) {
                    return $data->address1;
                })

                ->editColumn('size_name', function ($data) {
                    return $data->size_name;
                })
                
                ->addColumn('status', function ($row) {

                    $status = isset($row->status) ? $row->status : "";
                    $checked = ($status == 'active') ? "checked" : "";

                    $btn = "<div class='switch'> <label> <input class='onoffswitch-checkbox switchchange' name='onoffswitch' type='checkbox' data-status='$status' id='category$row->id' $checked  onclick='changeStatus($row->id)' data-tableid='employee-lenderslist'> <span class='lever'></span> </label> </div>";
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $viewUrl = url('admin/manage-store/view/' . $row->id);
                    $editURL = url('admin/manage-store/edit/' . $row->id);

                    $btn = "";
                    $btn = '<div class="dropdown">
                           <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <span class="icon-keyboard_control"></span>
                           </a>
                           <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                               <a class="dropdown-item" href="' . $viewUrl . '"   >View</a>
                               <a class="dropdown-item" href="' . $editURL . '"   >Edit</a>
                                <a class="dropdown-item" href="javascript:void(0);"  id=remove' . $row->id . ' data-url=' . url('admin/employee-delete') . ' data-name=' . $row->name . ' data-tableid="data-listing" onclick="deleteCategory(' . $row->id . ')" >Delete</a>
                           </div>
                       </div>';
                    return $btn;
                })
                ->rawColumns(['status', 'action', 'name'])
                ->make(true);

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getDetail($id)
    {
        try {
            $data['store_data'] = $this->storeMaster->where('id', $id)->first();
            $data['user_data'] = $this->user->where('store_id', $id)->first();

            return $data;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Delete user by Id
    */
    public static function delete($request)
    {
        try {
            DB::beginTransaction();
            $userData = StoreMaster::where(['id' => $request->id])->select('id', 'name', 'description', 'status')->first();
            if (!empty($userData)) {
                if($userData->update(array('status' => 'deleted'))){
                    User::where('store_id', $request->id)->delete();
                    DB::commit();
                }else{
                    DB::rollback();
                }
                $message = 'Store successfully deleted.';
                $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            } else {
                $response = ['success' => false, 'message' => 'User does not found.', 'error' => [], 'data' => []];
            }
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function update1($request)
    {
        try {
            $category = $this->storeMaster->where('id', $request->id);
            $userNewData = [
                'description' => $request['description'],
                'name' => $request['name'],
            ];
            $category->update($userNewData);

            $message = "Store successfully updated.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }


    public function update($request)
    {
        DB::beginTransaction();
        try {

            $userData = Auth::user();

            $category = $this->storeMaster->where('id', $request->id);

            $fileName = "";
            $profilePath = public_path() . '/uploads/users';

            if (!is_dir($profilePath)) {
                File::makeDirectory($profilePath, $mode = 0777, true, true);
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $fileExtension = strtolower($file->getClientOriginalExtension());
                $name = time().'.'.$fileExtension;
                $imageExist = public_path() . '/uploads/store/' . $name;
                $request->file('image')->move($profilePath, $name);

                $post['thumb_image'] = $name;
                $post['image'] = $name;
            }
            $post_data = '';
            if($request['open_time_']){
                $post_data = json_encode(array('open_time' =>$request['open_time_']), JSON_FORCE_OBJECT);
            }
            if($request['close_time_']){
                $post_data_close = json_encode(array('close_time' =>$request['close_time_']), JSON_FORCE_OBJECT);
            }

            $post['name'] = $request['name'];
            $post['short_name'] = $request['short_name'];
            $post['address1'] = $request['address1'];
            $post['address2'] = $request['address2'];
            $post['city_id'] = $request['city'];
            $post['state_id'] = $request['state'];
            $post['country_id'] = $request['country'];
            $post['pincode'] = $request['pincode'];
            $post['email'] = $request['email'];
            $post['phone_number'] = $request['phone_number'];
            $post['phone_number_country_code'] = $request['phone_number_country_code'];
            $post['open_time']      = $post_data;
            $post['close_time']     = $post_data_close;
            $post['pickup_delivery']= $request['pickup_delivery'];
            $post['delivery_charge']= $request['delivery_charge'];
            $post['free_del_upto']  = $request['free_del_km'];
            $post['description'] = $request['description'];
            $post['created_by']     = $userData->id;
            $post['updated_by']     = $userData->id;

            $category->update($post);

            if(!empty($request['user_id'])){
                $user = User::findOrfail($request['user_id']);
            }else{            
                $user = new User();
                $user->store_id = $request->id;
                $password = uniqid();
                $user->password = Hash::make($password);
                $send_mail = 1;

            }
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->phone_number = $request['phone_number'];
            $user->phone_number_country_code = $request['phone_number_country_code'];
            //$user->country = $request['country'];
            //$user->pincode = $request['pincode'];
            $user->user_type = 'store';
            $user->user_role = 'store';
            $user->save();
            DB::commit();
        
            $message = "Store successfully updated.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            // return $response;
            if(isset($send_mail)){

                $maildata = array(

                            'name' => $request['name'],
                            'email' => $request['email'],
                            'password' => $password,
                            'subject' => 'Store Detail'
                            );
              

                try{
                    $this->sendMail($maildata);
                }catch(Exception $e){

                }
            }
            if($request->segment(1) == 'store'){
                return redirect()->back()->with('success',  $message);

            }else{
                return redirect($request->segment(1).'/manage-store')->with('success',  $message);

            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $userData = Auth::user();

            $fileName = "";
            $profilePath = public_path() . '/uploads/users';
            if (!is_dir($profilePath)) {
                File::makeDirectory($profilePath, $mode = 0777, true, true);
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $fileExtension = strtolower($file->getClientOriginalExtension());
                $name = time().'.'.$fileExtension;
                $imageExist = public_path() . '/uploads/store/' . $fileName;
                $request->file('image')->move($profilePath, $name);

                $post['thumb_image'] = $name;
                $post['image'] = $name;
            }


            $post_data = '';
            if($request['open_time_']){
                $post_data = json_encode(array('open_time' =>$request['open_time_']), JSON_FORCE_OBJECT);
            }
            if($request['close_time_']){
                $post_data_close = json_encode(array('close_time' =>$request['close_time_']), JSON_FORCE_OBJECT);
            }


            $post['name'] = $request['name'];
            $post['short_name'] = $request['short_name'];
            $post['address1'] = $request['address1'];
            $post['address2'] = $request['address2'];
            $post['city_id'] = $request['city'];
            $post['state_id'] = $request['state'];
            $post['country_id'] = $request['country'];
            $post['pincode'] = $request['pincode'];
            $post['email'] = $request['email'];
            $post['password'] = Hash::make($request['password']);
            $post['phone_number'] = $request['phone_number'];
            $post['phone_number_country_code'] = $request['phone_number_country_code'];

            $post['open_time']      = $post_data;
            $post['close_time']     = $post_data_close;
            $post['pickup_delivery']= $request['pickup_delivery'];
            $post['delivery_charge']= $request['delivery_charge'];
            $post['free_del_upto']  = $request['free_del_km'];
            $post['description']    = $request['description'];
            $post['created_by']     = $userData->id;
            $post['updated_by']     = $userData->id;

            $store =  $this->storeMaster->create($post);

            $userData = new User();
            $userData->name = $request['name'];
            $userData->email = $request['email'];
            $userData->password = Hash::make($request['password']);
            $userData->phone_number = $request['phone_number'];
            $userData->phone_number_country_code = $request['phone_number_country_code'];   
            $userData->user_type = 'store';
            $userData->user_role = 'store';
            $userData->store_id = $store->id;
            $userData->save();
            DB::commit();
            $message = "Store added sucsessfully.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            // return $response;
            $maildata = array(

                        'name' => $request['name'],
                        'email' => $request['email'],
                        'password' => $request['password'],
                        'subject' => 'Store Detail'
                        );
          

            try{
                $this->sendMail($maildata);
            }catch(Exception $e){

            }
            return redirect('admin/manage-store')->with('success',  $message);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Change user status by Id
    */
    public static function changeStatus($request)
    {
        try {
            $userData = StoreMaster::where(['id' => $request->id])->select('id', 'name', 'description', 'status')->first();
            if (!empty($userData)) {
                $userData->update(array('status' => $request->status));
                User::where('store_id',  $request->id)->update(['status'=> $request->status]);
                $message = 'Status successfully changed.';
                $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            } else {
                $response = ['success' => false, 'message' => 'Store does not found.', 'error' => [], 'data' => []];
            }
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getManagers($id)
    {

        $data = "<option value=''>Select Manager</option>";
        // $managers = $this->manager->where('company_id', $id)->get();
        // foreach ($managers as $value) {
        //     $id = $value->id;
        //     $manager = $value->managerInfo->name;
        //     $data .= "<option value='$id'>$manager</option>";
        // }

        return $data;
    }

    public function getStoreMasterData()
    {


        try {

            $userData = StoreMaster::where(['status' => "active"])->get();
            if (!empty($userData)) {


                $response = ['success' => true,  'error' => [], 'data' => []];
            } else {
                $response = ['success' => false, 'message' => 'Store does not found.', 'error' => [], 'data' => []];
            }
            return view('storelist',$response);
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function sendMail($data) {
        try{

            Mail::send('emails.store_detail', ['data' => $data],
            function ($message) use ($data)
            {
                $message
                    ->from('raveena.synsoft@gmail.com')
                    ->to($data['email'])->subject($data['subject']);
            });
            return true;
        }catch(Exception $e){
            return false;
        }
   }


    public function getStoreGST(){
        try{            
            $store = $this->storeMaster->where('id', Auth::user()->store_id)->first();
            return $store;
        }catch(Exception $e){
            return false; 
        }
    }
    public function updataStoreGST($request){
        try{            
            $store = $this->storeMaster->where('id', Auth::user()->store_id)->update(['gst_per' => $request->gst_per]);
            return ['success' => true, 'message' => 'GST updated successfully'];
        }catch(Exception $e){
            return ['success' => false, 'message' => 'Something went wrong'];
        }
    }



    public function delivery_zone_list($request)
    {
        try {            
            $storeMaster = StorePostalCode::where('status', '!=', 'deleted')->where('store_id', Auth::user()->store_id);
            if (!empty($request->status)) {
                $storeMaster->where('status', $request->status);
            }

            $storeMaster = $storeMaster->latest()->get();
            $tableResult = Datatables::of($storeMaster)->addIndexColumn()

                ->filter(function ($instance) use ($request) {

                    if (!empty($request->name)) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(Str::lower($row['postal_code']), Str::lower(trim($request->name))) ? true : false;
                        });
                    }

                })
                ->editColumn('postal_code', function ($data) {
                    $postal_code = $data->postal_code;
                    
                    return $postal_code;
                })               
                ->editColumn('price', function ($data) {
                    return $data->price;
                })
                ->addColumn('status', function ($row) {

                    $status = isset($row->status) ? $row->status : "";
                    $checked = ($status == 'active') ? "checked" : "";

                    $btn = "<div class='switch'> <label> <input class='onoffswitch-checkbox switchchange' name='onoffswitch' type='checkbox' data-status='$status' id='category$row->id' $checked  onclick='changeStatus($row->id)' data-tableid='employee-lenderslist'> <span class='lever'></span> </label> </div>";
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $viewUrl = url('store/manage-delivery/view/' . $row->id);
                    $editURL = url('store/manage-delivery/edit/' . $row->id);

                    $btn = "";
                    $btn = '<div class="dropdown">
                           <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <span class="icon-keyboard_control"></span>
                           </a>
                           <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                               <a class="dropdown-item" href="' . $viewUrl . '"   >View</a>
                               <a class="dropdown-item" href="' . $editURL . '"   >Edit</a>
                                <a class="dropdown-item" href="javascript:void(0);"  id=remove' . $row->id . ' data-url=' . url('admin/employee-delete') . ' data-name=' . $row->name . ' data-tableid="data-listing" onclick="deleteCategory(' . $row->id . ')" >Delete</a>
                           </div>
                       </div>';
                    return $btn;
                })
                ->rawColumns(['status', 'action', 'postal_code'])
                ->make(true);

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }


    public function delivery_zone_add($request)
    {
        DB::beginTransaction();
        try {            
            
            $post['postal_code']  = implode(',', $request['zip_code']);
            $post['store_id']  = Auth::user()->store_id;
            $post['price']    = $request['price'];
            
            $store =  StorePostalCode::updateOrCreate(['id' => $request->id], $post);

            DB::commit();
            $response = ['success' => true, 'message' => 'Delivery Zone added successfully', 'error' => [], 'data' => []];
           
            return $response;

        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }


    public function getDeliveryDetail($id)
    {
        try {
            $data = StorePostalCode::where('id', $id)->first();
            return $data;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
    


    /*
    * Change delivery zone status by Id
    */
    public static function delivery_zone_status($request)
    {
        try {
            $userData = StorePostalCode::where(['id' => $request->id])->update(array('status' => $request->status));
            
            $message = 'Status successfully changed.';
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
}
