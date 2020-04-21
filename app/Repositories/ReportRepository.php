<?php

namespace App\Repositories;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Models\User;
use App\Models\Job;

use File;
use DB;
use DatePeriod;
use DateInterval;
use Datatables;

//use Your Model

/**
 * Class UserRepository.
 */
class ReportRepository {

    /**
     * @return string
     *  Return the model
     */
    private $user, $job;

    public function __construct(User $user, Job $job) {
        $this->user = $user;
        $this->job = $job;
    }


    /*
    * Get company list for admin
    */
    public function getCompanies($request) {
        try{
            $userType = (!empty($request->userType)) ? $request->userType : 'lender';
            $list = $this->user->select("users.id AS id", "c.company_title AS company_name", "users.email", "c.company_strength", "users.created_at")
                                    ->join('companies AS c', 'users.id', '=', 'c.user_id')->where('user_type',$userType)
                                    ->where('status','!=','deleted')->where('user_role','company')->latest();
            $list =$list->get();
            $tableResult = Datatables::of($list) ->addIndexColumn()
            ->rawColumns(['id','company_name', 'email', 'company_strength'])
            ->make(true);
            return $tableResult;

        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Download appraiser company list for admin
    */
    public function exportCompanyCsv($request) {
        try{
            $userType = (!empty($request->userType)) ? $request->userType : 'lender';
            $csvName = $userType.'-company.csv';

            $list = $this->user->select("users.id AS id", "c.company_title AS company_name", "users.email", "c.company_strength", "users.created_at")
                                    ->join('companies AS c', 'users.id', '=', 'c.user_id')->where('user_type', $userType)
                                    ->where('status','!=','deleted')->where('user_role','company')->orderBy('id', 'DESC')->get();
            $csvExporter = new \Laracsv\Export();
            $csvExporter->build($list, ['company_name'=>'Company Name', 'email' => 'Email', 'company_strength' => 'Company Strength'])->download($csvName);

        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

     /*
    * Get appraiser individual list for admin
    */
    public function getIndividuals($request) {
        try{
            $userType = (!empty($request->userType)) ? $request->userType : 'lender';
            if($userType=='lender'){
                $list = $this->user->selectRaw("users.id AS id, users.name, users.email, users.created_at,
                                            (SELECT IFNULL(SUM(j.fees_amount),0) FROM jobs AS j
                                            WHERE  j.job_state='completed' AND created_by = users.id) AS total_earning")
                                    ->where('user_type', $userType)->where('status','!=','deleted')->where('user_role','individual')->latest();
            }else{
                $list = $this->user->selectRaw("users.id AS id, users.name, users.email, users.created_at,
                                            (SELECT IFNULL(SUM(j.fees_amount),0) FROM job_bids AS jb JOIN jobs AS j ON j.id=jb.job_id
                                            WHERE jb.status='accepted' AND j.job_state='completed' AND jb.user_id=users.id AND jb.status ='accepted') AS total_earning")
                                    ->where('user_type', $userType)->where('status','!=','deleted')->where('user_role','individual')->latest();

                                    // $jobState = 'completed';
                                    // $totalEarning = Job::where('jobs.job_state',$jobState)->where('jobs.status', '!=', 'deleted')
                                    //             ->join('job_bids', 'jobs.id', '=', 'job_bids.job_id')
                                    //             ->where('job_bids.user_id', $id)->where('job_bids.status', 'accepted')->sum('jobs.fees_amount');
            }

            $list =$list->get();
            $tableResult = Datatables::of($list) ->addIndexColumn()
            ->rawColumns(['id','name', 'email', 'total_earning'])
            ->make(true);
            return $tableResult;

        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Download appraiser individual list for admin
    */
    public function exportIndividualsCsv($request) {
        try{
            $userType = (!empty($request->userType)) ? $request->userType : 'lender';
            $csvName = $userType.'-individuals.csv';

            $list = $this->user->selectRaw("users.id AS id, users.name, users.email, users.created_at,
                                            (SELECT IFNULL(SUM(jb.price),0) FROM job_bids AS jb JOIN jobs AS j ON j.id=jb.job_id
                                            WHERE jb.status='accepted' AND j.job_state='completed' AND jb.user_id=users.id) AS total_earning")
                                    ->where('user_type', $userType)->where('status','!=','deleted')->where('user_role','individual')->orderBy('id', 'DESC')->get();
            $csvExporter = new \Laracsv\Export();
            $csvExporter->build($list, ['name'=>'Full Name', 'email' => 'Email', 'total_earning' => 'Total Earning'])->download($csvName);

        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

     /*
    * Get total jobs list for admin
    */
    public function getTotalJobs($request) {
        try{
            $todayDate = date('Y-m-d');
            $jobs = $this->job->selectRaw("jobs.id, (
                                                        CASE WHEN jobs.job_state = 'completed' THEN 'Completed'
                                                             WHEN jobs.job_state = 'in_progress' THEN 'In Progress'
                                                             WHEN jobs.job_state = 'pending' AND jobs.due_date >='$todayDate' THEN 'Pending'
                                                             WHEN jobs.job_state = 'pending' AND jobs.due_date <'$todayDate' THEN 'Expired'
                                                        ELSE '-'
                                                        END
                                                    ) AS job_state,
                                                    u.name AS posted_by, DATE_FORMAT(jobs.created_at, '%d/%m/%Y %h:%i %p') AS posted_date, jobs.description, jobs.created_at")
                                    ->join('users AS u', 'u.id', '=', 'jobs.created_by')->where('u.status','!=','deleted')
                                    ->where('jobs.status','!=','deleted')->latest();
            if(!empty($request->posted_by)){
               $jobs->where('u.name', 'like', '%' . $request->posted_by . '%');
            }

            if(!empty($request->posted_date)){
               $postedDate = date('Y-m-d',strtotime($request->posted_date));
               $jobs->where('jobs.created_at','>=' , $postedDate.' 00:00:00' );
               $jobs->where('jobs.created_at','<=' , $postedDate.' 23:59:59' );
            }

            if(!empty($request->status)){
                $jobs->having('job_state', '=', $request->status);
            }
            $jobs =$jobs->get();
            $tableResult = Datatables::of($jobs) ->addIndexColumn()
            ->editColumn('description', function ($job) {
                $description = $job->description;
                if (strlen($description) > 50) {
                    $descriptionStr = substr($description, 0, 50);
                    $description = $descriptionStr . '<a href="javascript:void(0);" data-decription="' . $description . '" id="description_'.$job->id.'" title="" class="theme-color ml-1" onclick="readMoreModal(' . $job->id . ')">Read More</a>';
                }
                return $description;
            })
            ->editColumn('job_state', function ($job) {
                $status = '-';
                if ($job->job_state=='In Progress') {
                    $status = '<span class="text-info">'.$job->job_state.'</span>';
                }else if($job->job_state=='Completed'){
                    $status = '<span class="text-success">'.$job->job_state.'</span>';
                }else if($job->job_state=='Expired'){
                    $status = '<span class="text-danger">'.$job->job_state.'</span>';
                }else if($job->job_state=='Pending'){
                    $status = '<span class="text-warning">'.$job->job_state.'</span>';
                }
                return $status;
            })
            ->rawColumns(['id','posted_by', 'posted_date', 'description', 'job_state'])
            ->make(true);
            return $tableResult;

        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Export job csv
    */
    public function exportJobCsv($request) {
        try{
            $todayDate = date('Y-m-d');
            $jobs = $this->job->selectRaw("jobs.id,(
                                                    CASE WHEN jobs.job_state = 'completed' THEN 'Completed'
                                                            WHEN jobs.job_state = 'in_progress' THEN 'In Progress'
                                                            WHEN jobs.job_state = 'pending' AND jobs.due_date >='$todayDate' THEN 'Pending'
                                                            WHEN jobs.job_state = 'pending' AND jobs.due_date <'$todayDate' THEN 'Expired'
                                                    ELSE '-'
                                                    END
                                                    ) AS job_state,
                                        u.name AS posted_by, DATE_FORMAT(jobs.created_at, '%d/%m/%Y %h:%i %p') AS posted_date, jobs.description, jobs.created_at")
                                    ->join('users AS u', 'u.id', '=', 'jobs.created_by')->where('u.status','!=','deleted')
                                    ->where('jobs.status','!=','deleted')->orderBy('id', 'DESC');

             if(!empty($request->posted_by)){
               $jobs->where('u.name', 'like', '%' . $request->posted_by . '%');
            }

            if(!empty($request->posted_date)){
               $postedDate = date('Y-m-d',strtotime($request->posted_date));
               $jobs->where('jobs.created_at','>=' , $postedDate.' 00:00:00' );
               $jobs->where('jobs.created_at','<=' , $postedDate.' 23:59:59' );
            }

            if(!empty($request->status)){
                $jobs->having('job_state', '=', $request->status);
            }

            $jobs =$jobs->get();
            $csvExporter = new \Laracsv\Export();
            $csvExporter->build($jobs, ['posted_by'=>'Posted By', 'posted_date' => 'Posted Date', 'description' => 'Description', 'job_state'=>'Status'])->download('total-jobs.csv');

        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Get total revenue report for admin
    */
    public function getTotalRevenueReport($request) {
        try{
            $list = $this->job->selectRaw("jobs.created_by AS id, u.name,  u.created_at, jb.price, DATE_FORMAT(jb.completion_date, '%d/%m/%Y') AS completion_date, '-' AS payment_date")
                            ->join('users AS u', 'jobs.created_by', '=', 'u.id')
                            ->rightJoin('job_bids AS jb', 'jobs.id', '=', 'jb.job_id')
                            ->where('u.user_type','lender')->where('u.status','!=','deleted')->where('jobs.job_state','completed')->where('jb.status','accepted')->orderBy('jb.completion_date', 'DESC')->latest();
            if(!empty($request->name)){
                $list->where('u.name', 'like', '%' . $request->name . '%');
            }

            if(!empty($request->completion_date)){
                $date = date("Y-m-d", strtotime($request->completion_date));
                $from = $date . ' 00:00:00';
                $to = $date . ' 23:59:00';
                $list->whereBetween('jb.completion_date', [$from, $to]);
            }

            if(!empty($request->payment_date)){
                //$list->where('jb.payment_date', '<=', $request->payment_date);
            }


            $list =$list->get();
            $tableResult = Datatables::of($list) ->addIndexColumn()
            ->rawColumns(['id','name', 'price', 'completion_date', 'payment_date'])
            ->make(true);
            return $tableResult;

        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Get total revenue report for admin
    */
    public function exportTotalRevenueCsv($request) {
        try{
        $list = $this->job->selectRaw("jobs.created_by AS id, u.name,  u.created_at, jb.price, DATE_FORMAT(jb.completion_date, '%d/%m/%Y') AS completion_date, '-' AS payment_date")
                            ->join('users AS u', 'jobs.created_by', '=', 'u.id')
                            ->rightJoin('job_bids AS jb', 'jobs.id', '=', 'jb.job_id')
                            ->where('u.user_type','lender')->where('u.status','!=','deleted')->where('jobs.job_state','completed')->where('jb.status','accepted')->latest();
            if(!empty($request->name)){
                $list->where('u.name', 'like', '%' . $request->name . '%');
            }

            if(!empty($request->completion_date)){
                $list->where('jb.completion_date', '<=', $request->completion_date);
            }

            if(!empty($request->payment_date)){
                //$list->where('jb.payment_date', '<=', $request->payment_date);
            }
            $list =$list->get();
            $csvExporter = new \Laracsv\Export();
            $csvExporter->build($list, ['name'=>'Client Name', 'completion_date' => 'Completion Date', 'price' => 'Transaction Amount', 'payment_date'=>'Payment Date'])->download('total-revenue.csv');

        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
}
