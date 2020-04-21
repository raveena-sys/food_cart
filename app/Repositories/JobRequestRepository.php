<?php

namespace App\Repositories;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Models\User;
use App\Models\Company;
use App\Models\Manager;
use App\Models\Employee;
use App\Models\Messages;
use App\Models\Media;
use App\Models\UserAvailability;
use App\Models\JobRequestUpdate;
use File;
use DB;
use Illuminate\Support\Facades\Auth;

//use Your Model

/**
 * Class UserRepository.
 */
class JobRequestRepository {

    /**
     * @return string
     *  Return the model
     */
    private $user, $company, $manager, $employee, $userAvailability,$message,$jobRequest, $media;

    public function __construct(User $user, Company $company, Manager $manager, Employee $employee, UserAvailability $userAvailability,Messages $message,JobRequestUpdate $jobRequest, Media $media ) {
        $this->user = $user;
        $this->company = $company;
        $this->manager = $manager;
        $this->employee = $employee;
        $this->message = $message;
        $this->jobRequest = $jobRequest;
        $this->userAvailability = $userAvailability;
        $this->media = $media;
    }


    public  function saveJobRequest($request) {
       try {
            $result = $this->jobRequest->where(['from_id'=> $request['from_id'],'to_id'=>$request['to_id'],'file_name'=> $request['file_name']])->first();

            $msg['job_id'] = $request['job_id'];
            $msg['from_id'] =$request['from_id'];
            $msg['to_id'] =  $request['to_id'];
            $msg['file_name'] = $request['file_name'];
            $msg['comment'] = $request['comment'];
            $msg['status'] = !empty($result) ? 'Reviewed' :'Requested';
            $msg['parent_id'] = !empty($result) ? $result->id : null;

            $model =   $this->jobRequest->create($msg);
            $this->media->where('name', $request['file_name'])->where('media_for', 'comment')->update(['status' => 'used']);
            return $model;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function jobRequestData($id) {
         $jobs = $this->jobRequest->where('job_id', '=', $id)->with('children')->whereNull('parent_id')->get();
         return  $jobs;


    }
    public function jobRequestDetail($id) {
         $jobs = $this->jobRequest->where('id', '=', $id)->first();
         return  $jobs;


    }
}
