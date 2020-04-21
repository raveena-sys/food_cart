<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Job;
use App\Models\User;
use App\Models\Media;
use App\Models\JobMedia;
use App\Models\JobForms;
use App\Models\JobFavorites;
use App\Models\JobBid;
use Auth;

/**
 * Class CompanyRepository.
 */
class MyBidRepository
{

    private $job, $user, $media, $jobforms, $jobfav, $jobBid;

    public function __construct(Job $job, User $user, Media $media, Jobmedia $jobmedia, Jobforms $jobforms, JobFavorites $jobfav, JobBid $jobBid)
    {
        $this->job = $job;
        $this->user = $user;
        // $this->media = $media;
        // $this->jobmedia = $jobmedia;
        // $this->jobforms = $jobforms;
        // $this->jobfav = $jobfav;
        $this->jobBid = $jobBid;
    }



    /**
     * To get job bid list in appraiser side
     * @param type $request
     * @return string
     */
    public function getMyBidList($request)
    {
        $post = $request->all();
       try {
            if($post['list_type'] == 'bids_not_received'){
                $list = $this->job->selectRaw('jobs.id, jobs.due_date, job_bids.inspection_date, job_bids.inspection_time, jobs.fees_amount, users.name, users.profile_image')
                ->join('users', 'users.id', '=', 'jobs.created_by')
                ->join('job_bids', 'job_bids.job_id', '=', 'jobs.id')
                ->where('job_bids.status', '=', 'requested')
                ->where('jobs.status', '!=', 'deleted')
                ->where('jobs.job_state', '!=', 'completed')
                ->where('job_bids.user_id', Auth::user()->id);

            }else if($post['list_type'] == 'accepted'){

                $list = $this->job->selectRaw('jobs.id, jobs.due_date, job_bids.inspection_date, job_bids.inspection_time, jobs.fees_amount, users.name, users.profile_image')
                ->join('users', 'users.id', '=', 'jobs.created_by')
                ->join('job_bids', 'job_bids.job_id', '=', 'jobs.id')
                ->where('job_bids.status', '=', 'accepted')
                ->where('jobs.status', '!=', 'deleted')
                ->where('job_bids.user_id', Auth::user()->id);


            }else{
                $list = $this->job->selectRaw('jobs.id, jobs.due_date, job_bids.inspection_date, job_bids.inspection_time, jobs.fees_amount, users.name, users.profile_image')
                ->join('users', 'users.id', '=', 'jobs.created_by')->join('job_assignments', 'job_assignments.job_id', '=', 'jobs.id')
                ->join('job_bids', 'job_bids.job_id', '=', 'jobs.id')
                ->where('jobs.status', '!=', 'deleted')->where('job_assignments.assigned_to', Auth::user()->id)
                ->where('job_bids.user_id', Auth::user()->id)->where('job_assignments.status', 'active');
            }


                //Check cretical and accepted
                if ( $post['list_type'] == 'critical_tasks') {
                    $list->where('job_bids.status', '=', 'accepted');

                }
                //Check bid finished
                if ($post['list_type'] == 'finished') {
                    $list->where('jobs.job_state', '=', 'completed');
                }
                //Check bid not recieved
                if ($post['list_type'] == 'bids_not_received') {
                    $list->where('job_bids.status', '=', 'requested');
                }

                if ($request['list_type'] == 'bids_not_received') {
                    $list->where('jobs.due_date', '<=', date('Y-m-d'));
                }
                //Check critical condition
                if ($post['list_type'] == 'critical_tasks') {
                    $from = date('Y-m-d');
                    $to  = date('Y-m-d', strtotime(' + 2 days'));
                    $list->whereBetween('jobs.due_date', [$from, $to]);
                }

            if (!empty($post['q'])) {
                $list->where(function($query)use($post) {
                    $query->where('users.name', 'like', '%' . $post['q'] . '%')
                        ->orWhere('fees_amount', '=', $post['q']);
                });
             }

            $list->orderBy('job_bids.inspection_date', 'DESC');
            $list = $list->paginate(10);

            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get job detail in appraiser side
     * @param type $request
     * @return string
     */
    public function getMyBidDetail($request)
    {
        $post = $request->all();
        try {

            $data = $this->job->where('id', $request->id)->where('status', '!=', 'deleted')->with(['createdBy', 'jobBid', 'jobImages', 'jobDocs'])->first();
            $data->whereHas('jobBid', function ($q) {
                $q->where('job_bids.user_id', '=', Auth::user()->id);
            });

            return $data;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
}
