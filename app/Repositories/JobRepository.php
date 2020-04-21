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
use App\Models\JobAssignment;
use App\Models\JobInvitation;
use App\Models\Messages;
use App\Models\Threads;
use Auth;
use Datatables;
use DB;
use App\Common\Helpers;
use App\EmailQueue\CreateInvite;
use SebastianBergmann\Environment\Console;

/**
 * Class JobRepository.
 */
class JobRepository
{

    private $job, $user, $media, $jobforms, $jobfav, $jobBid, $jobAssignment, $jobInvitation, $message, $threads;

    public function __construct(Job $job, User $user, Media $media, Jobmedia $jobmedia, Jobforms $jobforms, JobFavorites $jobfav, JobBid $jobBid, JobAssignment $jobAssignment, JobInvitation $jobInvitation, Messages $message, Threads $threads)
    {
        $this->job = $job;
        $this->user = $user;
        $this->media = $media;
        $this->jobmedia = $jobmedia;
        $this->jobforms = $jobforms;
        $this->jobfav = $jobfav;
        $this->jobBid = $jobBid;
        $this->jobAssignment = $jobAssignment;
        $this->jobInvitation = $jobInvitation;
        $this->message = $message;
        $this->threads = $threads;
    }

    /**
     *
     * to get job list in admin side
     */
    public function jobList($request)
    {
        try {

            $jobs = $this->job->where('status', '!=', 'deleted')->with(['jobAssignments'])->latest()->get();
            $tableResult = Datatables::of($jobs)->addIndexColumn()->filter(function ($instance) use ($request) {

                if (!empty($request->created_by)) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return Str::contains(Str::lower($row['created_by']), Str::lower(trim($request->created_by))) ? true : false;
                    });
                }

                if (!empty($request->created_date)) {

                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        $date = str_replace('/', '-', $row['created_date']);
                        $date = date('m/d/Y', strtotime($date));
                        return Str::contains($date, $request->created_date) ? true : false;
                    });
                }

                if (!empty($request->status)) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return Str::contains(Str::lower($row['status']), Str::lower($request->status)) ? true : false;
                    });
                }
            })
                ->editColumn('created_by', function ($jobs) {
                    return ucfirst($jobs->createdBy->name);
                })
                ->editColumn('created_date', function ($jobs) {
                    return datetimeFormat($jobs->created_at);
                })
                ->editColumn('description', function ($jobs) {
                    $description = preg_replace("/[^A-Za-z0-9 ]/", "", $jobs->description);
                    if (strlen($description) > 50) {
                        $descriptionStr = substr($description, 0, 50);
                        $description = $descriptionStr . '<a href="javascript:void(0);" data-decription=' . $description . ' id="description"' . $jobs->id . '  title="" class="theme-color ml-1" onclick="readMoreModal(' . $jobs->id . ')">Read More</a>';
                    }
                    return $description;
                })
                ->editColumn('status', function ($jobs) {
                    if ($jobs->job_state == 'in_progress') {
                        $status = '<span class="text-info">In Progress</span>';
                    } elseif ($jobs->job_state == 'completed') {
                        $status = '<span class="text-success">Completed</span>';
                    } elseif ($jobs->due_date < date('Y-m-d') && count($jobs->jobAssignments) == 0) {
                        $status = '<span class="text-danger">Expired</span>';
                    } else {
                        $status = '<span class="text-warning">Pending</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {

                    $viewspath = url('admin/manage-job/job-view');
                    $view = url($viewspath . '/' . $row->id);
                    $edit = "EditCompanies($row->id)";
                    $btn = '<div class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="icon-keyboard_control"></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="' . $view . '">View</a>
                        </div>
                    </div>';
                    return $btn;
                })
                // <a class="dropdown-item" href="javascript:void(0);" onclick="'.$delete.'">Delete</a>
                ->rawColumns(['status', 'action', 'description'])
                ->make(true);

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     *
     * to get job list in admin side
     */
    public function totalContractsList($request)
    {
        try {
           if($request->user_type=='lender'){
                $jobs = $this->job->where('status', '!=', 'deleted')->with(['jobAssignments']);
                $jobs->whereHas('jobAssignments', function ($q) use ($request) {
                    $q->where('status',  '=', 'active')
                        ->where('assigned_by', $request->user_id);
                });
            }else{
                $jobs = $this->job->where('status', '!=', 'deleted')->with(['jobAssignments']);
                $jobs->whereHas('jobAssignments', function ($q) use ($request) {
                    $q->where('status',  '=', 'active')
                        ->where('assigned_to', $request->user_id);
                });
            }

            $tableResult = Datatables::of($jobs)->addIndexColumn()->filter(function ($instance) use ($request) {

                if (!empty($request->created_by)) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return Str::contains(Str::lower($row['created_by']), Str::lower(trim($request->created_by))) ? true : false;
                    });
                }

                if (!empty($request->created_date)) {

                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        $date = str_replace('/', '-', $row['created_date']);
                        $date = date('m/d/Y', strtotime($date));
                        return Str::contains($date, $request->created_date) ? true : false;
                    });
                }

                if (!empty($request->status)) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return Str::contains(Str::lower($row['status']), Str::lower($request->status)) ? true : false;
                    });
                }
            })
                ->editColumn('created_by', function ($jobs) {
                    return ucfirst($jobs->createdBy->name);
                })
                ->editColumn('created_date', function ($jobs) {
                    return datetimeFormat($jobs->created_at);
                })
                ->editColumn('description', function ($jobs) {
                    $description = preg_replace("/[^A-Za-z0-9 ]/", "", $jobs->description);
                    if (strlen($description) > 50) {
                        $descriptionStr = substr($description, 0, 50);
                        $description = $descriptionStr . '<a href="javascript:void(0);" data-decription=' . $description . ' id="description"' . $jobs->id . '  title="" class="theme-color ml-1" onclick="readMoreModal(' . $jobs->id . ')">Read More</a>';
                    }
                    return $description;
                })
                ->editColumn('status', function ($jobs) {
                    if ($jobs->job_state == 'in_progress') {
                        $status = '<span class="text-info">In Progress</span>';
                    } elseif ($jobs->job_state == 'completed') {
                        $status = '<span class="text-success">Completed</span>';
                    } elseif ($jobs->due_date < date('Y-m-d')) {
                        $status = '<span class="text-danger">Expired</span>';
                    } else {
                        $status = '<span class="text-warning">Pending</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {

                    $viewspath = url('admin/manage-job/job-view');
                    $view = url($viewspath . '/' . $row->id);
                    $edit = "EditCompanies($row->id)";
                    $btn = '<div class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="icon-keyboard_control"></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="' . $view . '">View</a>
                        </div>
                    </div>';
                    return $btn;
                })
                // <a class="dropdown-item" href="javascript:void(0);" onclick="'.$delete.'">Delete</a>
                ->rawColumns(['status', 'action', 'description'])
                ->make(true);

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function viewCompanyDetails($id, $type)
    {
        try {
            $company = $this->user->with('companyDetails')->where(['user_type' => $type, 'user_role' => 'company', 'id' => $id])->first();
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $company];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function jobDetail($id)
    {
        try {
            $jobs = $this->job->where('id', '=', $id)->first();
            return $jobs;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function createJob($request)
    {
        DB::beginTransaction();
        try {

            $data['code'] = generateRandomString(10);
            $data['title'] = $request['title'];
            $data['assignment_type'] = $request['assignment_type'];
            $data['fees_amount'] = $request['fees_amount'];
            $data['personal_licence_number'] = $request['personal_licence_number'];
            $data['plot_size'] = $request['plot_size'];
            $data['gross_leasable_area'] = $request['gross_leasable_area'];
            $data['due_date'] = date("Y-m-d", strtotime($request['due_date']));
            $data['job_licence_number'] = $request['job_licence_number'];
            $data['age_of_property'] = !empty($request['age_of_property']) ? $request['age_of_property'] : 0;
            $data['no_of_required_appraiser'] = $request['no_of_required_appraiser'];
            $data['is_form_required'] = ($request['is_form_required'] == 'yes') ? 1 : 0;
            $data['address'] = $request['address'];
            $data['description'] = $request['description'];
            $data['borrower_name'] = $request['borrower_name'];
            $data['borrower_email'] = $request['borrower_email'];
            $data['borrower_phone_number'] = $request['borrower_phone_number'];
            $data['form_value'] = $request['form_value'];
            $data['is_media_uploaded'] = 0;
            $data['created_by'] = Auth::user()->id;
            $data['updated_by'] = Auth::user()->id;
            $job = $this->job->create($data);

            $tagArray = explode(",", $request['form_value']);

            if (!empty($request['files'])) {
                $imageArray = explode(",", $request['files']);
                $count = checkImageCount($imageArray);
                if (!$count) {
                    $response = ['success' => false, 'message' => "Media not exist.", 'error' => [], 'data' => []];
                    return $response;
                }
                $this->saveImages($imageArray, $job->id, 'image', 'job');
            }

            $this->saveTags($tagArray, $job->id);
            DB::commit();
            $response = ['success' => true, 'message' => 'Job successfully added.', 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function updateJob($request)
    {
        DB::beginTransaction();
        try {
            $data = $this->job->where(['id' => $request['id']]);

            $newData = [
                'code' => 12345,
                'title' => $request['title'],
                'assignment_type' => $request['assignment_type'],
                'fees_amount' => $request['fees_amount'],
                'personal_licence_number' => $request['personal_licence_number'],
                'plot_size' => $request['plot_size'],
                'gross_leasable_area' => $request['gross_leasable_area'],
                'due_date' => date("Y-m-d", strtotime($request['due_date'])),
                'job_licence_number' => $request['job_licence_number'],
                'age_of_property' => $request['age_of_property'],
                'no_of_required_appraiser' => $request['no_of_required_appraiser'],
                'is_form_required' => ($request['is_form_required'] == 'yes') ? 1 : 0,
                'address' => $request['address'],
                'description' => $request['description'],
                'borrower_name' => $request['borrower_name'],
                'borrower_email' => $request['borrower_email'],
                'borrower_phone_number' => $request['borrower_phone_number'],
                'is_media_uploaded' => 0,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];

            $job = $data->update($newData);

            $tagArray = explode(",", $request['form_value']);

            if (!empty($request['files'])) {
                $imageArray = explode(",", $request['files']);
                $oldImages = $this->jobmedia->where('job_id', $request['id'])->where('user_id', Auth::user()->id)->delete();
                if ($oldImages) {
                    $this->saveImages($imageArray, $request['id'], 'image', 'job');
                } else {
                    $this->saveImages($imageArray, $request['id'], 'image', 'job');
                }
            } else {
                $oldImages = $this->jobmedia->where('job_id', $request['id'])->where('user_id', Auth::user()->id)->delete();
            }
            $oldTags = $this->jobforms->where('job_id', $request['id'])->delete();
            if ($oldTags) {
                $this->saveTags($tagArray, $request['id']);
            }
            DB::commit();
            $response = ['success' => true, 'message' => 'Job update successfully.', 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function saveMedia($request)
    {
        try {
            $this->media->create($request);
            return $response = ['success' => true, 'message' => 'media uploaded Successfully.', 'error' => [], 'data' => []];
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function saveImages($jobimages, $job_id, $mediaType = '', $section_type = '')
    {
        try {
            DB::beginTransaction();
            foreach ($jobimages as $item) {
                $is_media_uploaded = 0;
                if (!empty($item)) {

                    $ext = pathinfo($item, PATHINFO_EXTENSION);
                    if ($ext == 'xlsx' | $ext == 'xls') {
                        $mediaType = 'xls';
                    }
                    if ($ext == 'doc' | $ext == 'docx') {
                        $mediaType = 'doc';
                    }
                    if ($ext == 'pdf') {
                        $mediaType = 'pdf';
                    }
                    if ($ext == 'xml') {
                        $mediaType = 'xml';
                    }
                    if ($ext == 'csv') {
                        $mediaType = 'csv';
                    }
                    if ($ext == 'txt') {
                        $mediaType = 'txt';
                    }
                    if ($ext == 'env') {
                        $mediaType = 'env';
                    }
                    if ($ext == 'jpeg' | $ext == 'jpg' | $ext == 'png') {
                        $mediaType = 'image';
                        $is_media_uploaded = 1;
                        $this->job->where('id', $job_id)->update(['is_media_uploaded' => $is_media_uploaded]);
                    }
                    $jobitem['job_id'] = $job_id;
                    $jobitem['user_id'] = Auth::user()->id;
                    $jobitem['media_type'] = $mediaType;
                    $jobitem['section_type'] = $section_type;
                    $jobitem['name'] = $item;
                    $this->jobmedia->create($jobitem);
                    $this->media->where('name', $item)->where('media_for', 'job')->update(['status' => 'used']);

                    DB::commit();
                }
            }

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage(), 'short_msg' => 'Invalid Order Image')], 'data' => []];
            return $response;
        }
    }

    public function saveTags($jobtags, $job_id)
    {
        try {
            foreach ($jobtags as $tag) {

                if (!empty($tag)) {
                    $jobtag['job_id'] = $job_id;
                    $jobtag['name'] = $tag;
                    $this->jobforms->create($jobtag);
                }
            }
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => []];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage(), 'short_msg' => 'Invalid Tags')], 'data' => []];
        }
    }

    /**
     * to get job list in lender side
     * @param type $request
     * @return boolean
     */
    public function getJobList($request)
    {
        $post = $request->all();
        try {

            // $list = $this->job->where('created_by', Auth::user()->id)->where('no_of_required_appraiser','>', 'job_bid_count')->with(['createdBy', 'jobImages'])->latest();
            $list = Job::select(DB::raw('*'))
                ->where('created_by', '=', Auth::user()->id)
                ->where('job_state', '=', 'pending')
                //->where('no_of_required_appraiser', '>', DB::raw('(select count(*) from job_bids where job_bids.job_id=jobs.id and status="accepted")'))
                ->with(['createdBy', 'jobImages'])
                ->latest();
            if (!empty($post['posted_by'])) {
                $list->whereHas('createdBy', function ($q) use ($post) {
                    $q->where('user_role', '=', $post['posted_by']);
                });
            }
            if (!empty($post['due_date'])) {
                if ($post['due_date'] == 'this_week') {
                    $from = date('Y-m-d');
                    $to = date('Y-m-d', strtotime('sunday this week'));
                    $list->whereBetween('due_date', [$from, $to]);
                }
                if ($post['due_date'] == 'this_month') {
                    $from = date('Y-m-d');
                    $to = date('Y-m-t');
                    $list->whereBetween('due_date', [$from, $to]);
                }

                if ($post['due_date'] == 'this_today') {
                    $list->where('due_date', date('Y-m-d'));
                }
                if ($post['due_date'] == 'next_week') {
                    $from = date('Y-m-d', strtotime('monday next week'));
                    $to = date('Y-m-d', strtotime('sunday next week'));
                    $list->whereBetween('due_date', [$from, $to]);
                }
            }
            if (!empty($post['posted_date'])) {

                if ($post['posted_date'] == 'this_week') {
                    $firstday = date('Y-m-d', strtotime("this week"));
                    $lastday = date('Y-m-d', strtotime($firstday . ' + 6 days'));
                    $list->whereBetween('created_at', [$firstday, $lastday]);
                }

                if ($post['posted_date'] == 'this_month') {
                    $firstday =  date("Y-m-d", strtotime("first day of this month"));
                    $lastday =  date("Y-m-d", strtotime("last day of this month"));
                    $list->whereBetween('created_at', [$firstday, $lastday]);
                }
            }
            if (!empty($post['min_budget'])) {
                $list->where('fees_amount', '>=', $post['min_budget']);
            }

            if (!empty($post['max_budget'])) {
                $list->where('fees_amount', '<=', $post['max_budget']);
            }
            if (!empty($post['with_photo'])) {
                $list->whereHas('jobImages', function ($q) use ($post) {
                    $q->where('media_type', '=', 'image');
                });
            }
            if (!empty($post['search_job'])) {
                $title = $post['search_job'];
                $list->where('title', 'like', '%' . $title . '%');
            }

            if (!empty($post['search_location'])) {
                $title = $post['search_location'];
                $list->where('address', 'like', '%' . $title . '%');
            }
            $list = $list->paginate(10);
            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * to get job list in appraiser side
     * @param type $request
     * @return string
     */
    public function getApraiserJobsList($request)
    {
        $post = $request->all();
        try {

            $jobIds = [];
            $allMybids = $this->jobBid->select('job_id')->where('user_id', Auth::user()->id)->get()->toArray();
            foreach ($allMybids as $bid) {
                $jobIds[] = $bid['job_id'];
            }

            $list = $this->job->where(['status' => 'active'])->where('due_date', '>=', date('Y-m-d'))->whereColumn('no_of_required_appraiser', '>', 'job_bid_count')->whereNotIn('id', $jobIds)->latest();
            if (!empty($post['posted_by'])) {
                $list->whereHas('createdBy', function ($q) use ($post) {
                    $q->where('user_role', '=', $post['posted_by']);
                });
            }
            if (!empty($post['due_date'])) {
                if ($post['due_date'] == 'this_week') {
                    $from = date('Y-m-d');
                    $to = date('Y-m-d', strtotime('sunday this week'));
                    $list->whereBetween('due_date', [$from, $to]);
                }

                if ($post['due_date'] == 'this_month') {
                    $from = date('Y-m-d');
                    $to = date('Y-m-t');
                    $list->whereBetween('due_date', [$from, $to]);
                }

                if ($post['due_date'] == 'this_today') {
                    $list->where('due_date', date('Y-m-d'));
                }
                if ($post['due_date'] == 'next_week') {
                    $from = date('Y-m-d', strtotime('monday next week'));
                    $to = date('Y-m-d', strtotime('sunday next week'));
                    $list->whereBetween('due_date', [$from, $to]);
                }
            }

            if (!empty($post['posted_date'])) {

                if ($post['posted_date'] == 'this_week') {
                    $firstday = date('Y-m-d', strtotime("this week"));
                    $lastday = date('Y-m-d', strtotime($firstday . ' + 6 days'));
                    $list->whereBetween('created_at', [$firstday, $lastday]);
                }

                if ($post['posted_date'] == 'this_month') {
                    $firstday =  date("Y-m-d", strtotime("first day of this month"));
                    $lastday =  date("Y-m-d", strtotime("last day of this month"));
                    $list->whereBetween('created_at', [$firstday, $lastday]);
                }
            }

            if (!empty($post['min_budget'])) {
                $list->where('fees_amount', '>=', $post['min_budget']);
                // $list->whereBetween('fees_amount', [$post['min_budget'], $post['max_budget']]);
            }

            if (!empty($post['max_budget'])) {
                $list->where('fees_amount', '<=', $post['max_budget']);
                // $list->whereBetween('fees_amount', [$post['min_budget'], $post['max_budget']]);
            }

            if (!empty($post['with_photo'])) {
                $list->whereHas('jobImages', function ($q) use ($post) {
                    $q->where('media_type', '=', 'image');
                });
            }

            if (!empty($post['search_job'])) {
                $title = $post['search_job'];
                $list->where('title', 'like', '%' . $title . '%');
            }

            if (!empty($post['search_location'])) {
                $title = $post['search_location'];
                $list->where('address', 'like', '%' . $title . '%');
            }

            $list = $list->paginate(10);
            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * function get job list for front
     * @param type $request
     * @return array
     */
    public function getfrontJobList($request)
    {
        try {

            $jobIds = [];
            $currentDate = date('Y-m-d');
            if (Auth::user()) {
                $allMybids = $this->jobBid->select('job_id')->where('user_id', Auth::user()->id)->get()->toArray();
                foreach ($allMybids as $bid) {
                    $jobIds[] = $bid['job_id'];
                }
            }

            $list = $this->job->where('status', 'active')->where('job_state', 'pending')->where('due_date' ,'>=', $currentDate)->whereColumn('no_of_required_appraiser', '>', 'job_bid_count')->whereNotIn('id', $jobIds)->latest()->take(10);
            if (!empty($request['address'])) {
                $list->where('address', 'like', '%' . $request['address'] . '%');
            }
            $list = $list->get();
            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * to get saved job detail or favourite job detail
     * @param type $id
     * @return boolean
     */
    public function favouriteJob($id)
    {
        try {
            $job = $this->jobfav->where(['job_id' => $id, 'user_id' => Auth::user()->id])->first();
            if ($job) {
                $job->delete();
                return $response = ['success' => true, 'message' => 'Job unfavourited successfully.', 'data' => []];
            } else {
                $data = [];
                $data['user_id'] = Auth::user()->id;
                $data['job_id'] = $id;
                $favouriteModel = $this->jobfav->create($data);
                return $response = ['success' => true, 'message' => 'Job favourited successfully.', 'data' => $favouriteModel];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * Add or Remove job from favourite list
     * @param type $id
     * @return boolean
     */
    public function addToFavourite($request)
    {
        try {
            $post = $request->all();
            $favJob = $this->jobfav->where(['job_id' => $post['id'], 'user_id' => Auth::user()->id])->first();
            if (!empty($favJob)) {
                if ($post['is_favourite'] == 0) {
                    $favJob->delete();
                    return $response = ['success' => true, 'data' => [], 'message' => 'Job successfully removed from your favourite list.'];
                } else {
                    return $response = ['success' => false, 'data' => [], 'message' => 'Job already added in your favourite list.'];
                }
            } else {
                $data = [];
                $data['user_id'] = Auth::user()->id;
                $data['job_id'] = $post['id'];
                $favouriteModel = $this->jobfav->create($data);
                return $response = ['success' => true, 'data' => $favouriteModel, 'message' => 'Job successfully added in your favourited list.'];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * to get saved job list
     * @param type $request
     * @return string
     */
    public function getSavedJobList($request)
    {
        $post = $request->all();
        try {
            $list = $this->jobfav->where(['user_id' => Auth::user()->id])->with('job', 'job.createdBy')->latest();
            /**
             * filter for which type of user posted job
             */
            if (!empty($post['posted_by'])) {
                $list->whereHas('job.createdBy', function ($q) use ($post) {
                    $q->where('user_role', '=', $post['posted_by']);
                });
            }
            /**
             * filter by due date
             */
            if (!empty($post['due_date'])) {

                if ($post['due_date'] == 'this_week') {
                    $from = date('Y-m-d', strtotime("previous monday"));
                    $to = date('Y-m-d', strtotime("next sunday"));
                    $list->whereHas('job', function ($q) use ($from, $to) {
                        $q->whereBetween('due_date', [$from, $to]);
                    });
                }
                if ($post['due_date'] == 'next_week') {
                    $from = date('Y-m-d', strtotime("next monday"));
                    $to = date('Y-m-d', strtotime("sunday next week"));
                    $list->whereHas('job', function ($q) use ($from, $to) {
                        $q->whereBetween('due_date', [$from, $to]);
                    });
                }
                if ($post['due_date'] == 'this_month') {
                    $from = date('Y-m-01');
                    $to = date('Y-m-d', strtotime("last day of this month"));
                    $list->whereHas('job', function ($q) use ($from, $to) {
                        $q->whereBetween('due_date', [$from, $to]);
                    });
                }

                if ($post['due_date'] == 'this_today') {
                    $list->whereHas('job', function ($q) {
                        $q->where('due_date', date('Y-m-d'));
                    });
                }
            }
            /**
             * filter by posted date
             */
            if (!empty($post['posted_date'])) {

                if ($request['posted_date'] == 'this_week') {
                    $firstday = date('Y-m-d', strtotime("this week"));
                    $lastday = date('Y-m-d', strtotime($firstday . ' + 6 days'));
                    $from = $firstday . ' 00:00:00';
                    $to = $lastday . ' 23:59:00';
                    $list->whereHas('job', function ($q) use ($from, $to) {
                        $q->whereBetween('created_at', [$from, $to]);
                    });
                    //$list->whereBetween('created_at', [$from, $to]);
                }
                if ($request['posted_date'] == 'this_month') {
                    $firstday =  date("Y-m-d", strtotime("first day of this month"));
                    $lastday =  date("Y-m-d", strtotime("last day of this month"));
                    $from = $firstday . ' 00:00:00';
                    $to = $lastday . ' 23:59:00';
                    $list->whereHas('job', function ($q) use ($from, $to) {
                        $q->whereBetween('created_at', [$from, $to]);
                    });
                    //$list->whereBetween('created_at', [$from, $to]);
                }
            }

            if (!empty($post['min_budget'])) {

                $list->whereHas('job', function ($q) use ($post) {
                    $q->where('fees_amount', '>=', $post['min_budget']);
                });
            }

            if (!empty($post['max_budget'])) {
                $list->whereHas('job', function ($q) use ($post) {
                    $q->where('fees_amount', '<=', $post['max_budget']);
                });
            }

            if (!empty($post['with_photo'])) {
                $list->whereHas('job', function ($q) use ($post) {
                    $q->where('is_media_uploaded', '=', 1);
                });
            }

            if (!empty($post['search_title'])) {
                $list->whereHas('job', function ($q) use ($post) {
                    $q->where('title', 'like', '%' . $post['search_title'] . '%')
                        ->orWhereHas('createdBy', function ($q) use ($post) {
                            $q->where('name', 'like', '%' . $post['search_title'] . '%');
                        });
                });
            }

            $list = $list->paginate(10);
            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * function to send bid request
     * @param type $request
     * @return boolean
     */
    public function saveBidRequest($request)
    {
        try {
            $companyId = '';
            $post = $request->all();
            $userId = Auth::user()->id;
            $post['user_id'] = $userId;
            $post['inspection_date'] = date('Y-m-d', strtotime($post['inspection_date']));
            $post['inspection_time'] = date('H:i', strtotime($post['inspection_time']));
            $bidModel = $this->jobBid->where(['user_id' => $userId, 'job_id' => $post['job_id']])->first();
            $jobDueDate = $this->job->where(['id' => $post['job_id']])->first();
            if ($jobDueDate['due_date'] >= $post['inspection_date']) {
                if (!$bidModel) {
                    //Find company id
                    if (!empty($userId)) {
                        $userData =  $this->user->where(['id' => $userId])->first();
                        if ($userData->user_role == 'employee') {
                            $companyId = $userData->employeeDetails->company_id;
                        } else if ($userData->user_role == 'manager') {
                            $companyId = $userData->managerDetails->company_id;
                        } else if ($userData->user_role == 'company') {
                            $companyId = $userData->companyDetails->id;
                        } else if ($userData->user_role == 'individual') {
                            $companyId = NULL;
                        }
                        $post['company_id'] = $companyId;
                    }
                    $bid = $this->jobBid->create($post);
                    return $response = ['success' => true, 'message' => 'Thank You for your bid, we will let you know when you have the assignment.', 'data' => []];
                } elseif (!empty($post['bid_id'])) {
                    $bid = $bidModel->update($post);
                    $bidModel->inspection_date = getDateOnly($bidModel->inspection_date);
                    $bidModel->inspection_time = stingTimeFormat($bidModel->inspection_time);
                    return $response = ['success' => true, 'message' => 'Bid successfully Updated.', 'data' => $bidModel];
                } else {
                    return $response = ['success' => false, 'message' => 'Job has been expired.', 'data' => []];
                }
            } else {
                return $response = ['success' => false, 'message' => 'Date should be less than equal to the due date.', 'data' => []];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
            return $response;
        }
    }

    /**
     * function to get bid detail
     * @param type $jobId
     * @return type
     */
    public function bidDetail($jobId)
    {
        return $this->jobBid->where(['user_id' => Auth::user()->id, 'job_id' => $jobId])->first();
    }

    /**
     * job applied list
     * @param type $request
     * @return array
     */
    public function totalJobAppliedList($request)
    {
        try {
            $post = $request->all();
            $list = $this->jobBid->where(['user_id' => Auth::user()->id])->with('jobDetail', 'jobDetail.createdBy')->latest();
            if (!empty($post['praposal_date'])) {
                if ($post['praposal_date'] == 'this_week') {
                    $firstday = date('Y-m-d', strtotime("this week"));
                    $lastday = date('Y-m-d', strtotime($firstday . ' + 6 days'));
                    $list->whereBetween('inspection_date', [$firstday, $lastday]);
                }
                if ($post['praposal_date'] == 'last_week') {
                    $firstday = date('Y-m-d', strtotime("last week"));
                    $lastday = date('Y-m-d', strtotime($firstday . ' + 6 days'));
                    $list->whereBetween('inspection_date', [$firstday, $lastday]);
                }
                if ($post['praposal_date'] == 'yesterday') {
                    $previousDaye = date('Y-m-d', strtotime("-1 days"));
                    $list->where('inspection_date', $previousDaye);
                }
                if ($post['praposal_date'] == 'older') {
                    $today = "'" . date('Y-m-d') . "'";
                    $list->whereRaw("DATE(inspection_date) < $today");
                }
            }
            if (!empty($post['min_price'])) {
                $list->where('price', '>=', $post['min_price']);
            }

            if (!empty($post['max_price'])) {
                $list->where('price', '<=', $post['max_price']);
            }

            if (!empty($post['with_photo'])) {
                $list->whereHas('jobDetail', function ($q) {
                    $q->where('is_media_uploaded', '=', 1);
                });
            }

            if (isset($post['name']) && $post['name']) {
                $list->whereHas('jobDetail', function ($q) use ($post) {
                    $q->whereHas('createdBy', function ($q) use ($post) {
                        $q->where('name', 'like', '%' . $post['name'] . '%');
                    });
                });
            }

            if (isset($post['price']) && $post['price']) {
                $bidPrice = explode(",", $post['price']);

                $list->WhereBetween('price', [$bidPrice[0], $bidPrice[1]]);
            }

            if (isset($post['latest']) && $post['latest'] == "on") {
                $list->orderby('created_at', 'desc');
            }
            if (isset($post['latest']) && $post['latest'] == "off") {
                $list->orderby('created_at', 'asc');
            }

            if (!empty($post['search_title'])) {

                $list->whereHas('jobDetail', function ($q) use ($post) {
                    $q->where('title', 'like', '%' . $post['search_title'] . '%')
                        ->orWhereHas('createdBy', function ($q) use ($post) {
                            $q->where('name', 'like', '%' . $post['search_title'] . '%');
                        });
                    $q->Orwhere('price', '=', $post['search_title']);
                });
            }
            $list = $list->paginate(10);
            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
            return $response;
        }
    }

    /**
     * employee  list
     * @param type $request
     * @return array
     */
    public function totalEmployeeList($request)
    {
        try {
            $post = $request->all();
            $list = $this->user->where(['user_type' => 'appraiser'])
                ->where(function ($query) {
                    $query->where('user_role', '=', 'manager')
                        ->orWhere('user_role', '=', 'employee');
                });
            if (isset($post['name']) && $post['name']) {

                $list->where('name', 'like', '%' . $post['name'] . '%');
            }

            return $list = $list->paginate(10);
        } catch (Exception $ex) {
        }
    }
    /**
     * Job Praposals  list
     * @param type $request
     * @return array
     */
    public function getJobPraposalsList($request)
    {
        try {
            $post = $request->all();
            $list = $this->jobBid->where(['job_id' => $post['id']])->with('jobBidUserDetail');
            $list->orderby('created_at', 'DESC');


            if (isset($post['name']) && $post['name']) {
                $list->whereHas('jobBidUserDetail', function ($q) use ($post) {
                    $q->where('name', 'like', '%' . $post['name'] . '%');
                });
            }

            if (isset($post['price']) && $post['price']) {
                $bidPrice = explode(",", $post['price']);

                $list->WhereBetween('price', [$bidPrice[0], $bidPrice[1]]);
            }

            if (isset($post['latest']) && $post['latest'] == "on") {
                $list->orderby('created_at', 'DESC');
            }
            if (isset($post['latest']) && $post['latest'] == "off") {
                $list->orderby('created_at', 'ASC');
            }

            if (!empty($post['search_title'])) {
                $list->where('description', 'like', '%' . $post['search_title'] . '%')
                    ->orWhereHas('jobBidUserDetail', function ($q) use ($post) {
                        $q->where('name', 'like', '%' . $post['search_title'] . '%');
                    });
            }

            return $list = $list->paginate(10);
        } catch (Exception $ex) {
        }
    }

    /**
     * to get applied job detail
     * @param type $id
     * @return type
     */
    public function jobAppliedDetail($id)
    {
        return $this->jobBid->where(['id' => $id])->first();
    }

    public function unFavouriteJob($id)
    {
        try {
            $job = $this->jobfav->where(['user_id' => Auth::user()->id, 'job_id' => $id])->first();
            if ($job) {
                $job->delete();
                $response = ['success' => true, 'message' => 'Job successfully removed from your favourite list.', 'data' => []];
                return $response;
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
            return $response;
        }
    }

    /**
     * to check job added in favourite
     * @param type $id
     * @return type
     */
    public function checkFavouriteExist($id)
    {
        return $this->jobfav->where(['job_id' => $id, 'user_id' => Auth::user()->id])->first();
    }

    public function awardBid($id)
    {
        DB::beginTransaction();
        try {
            $companyId = '';
            $data =  $this->jobBid->where(['id' => $id])->first();
            // $userData =  $this->user->where(['id' => $data->user_id])->first();
            // if(!empty($userData)){
            //     if($userData->user_role=='employee'){
            //       $companyId = $userData->employeeDetails->company_id;
            //     }else if($userData->user_role=='manager'){
            //       $companyId = $userData->managerDetails->company_id;
            //     }else if($userData->user_role=='company'){
            //         $companyId = $userData->companyDetails->id;
            //     }
            // }

            $jobdata =  $this->job->where(['id' => $data->job_id])->first();
            $data->update(['status' => 'accepted']);
            $jobdata->update(['job_bid_count' => ($jobdata->job_bid_count) + 1]);
            if ($jobdata->no_of_required_appraiser == $jobdata->job_bid_count) {
                $jobdata->update(['job_state' => 'in_progress']);
            }
            $assignmentData = [];
            $assignmentData['assigned_to'] = $data->user_id;
            $assignmentData['job_id'] = $data->job_id;
            $assignmentData['job_bid_id'] = $data->id;
            $assignmentData['assigned_by'] = Auth::user()->id;
            $assignmentData['company_id'] = $data->company_id;
            $assignmentData['date'] =  $data->inspection_date;
            $jobAssignmentDetail = $this->jobAssignment->create($assignmentData);

            DB::commit();
            return $jobdata;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * to get open contracts list in lender side
     * @param type $request
     * @return boolean
     */
    public function getLenderOpenContractList($request)
    {
        try {

            $list = Job::select(DB::raw('*'))
                ->where('created_by', '=', Auth::user()->id)
                ->where('job_state', '=', 'in_progress')
                //->where('no_of_required_appraiser', '=', DB::raw('(select count(*) from job_bids where job_bids.job_id=jobs.id and status="accepted")'))
                ->with(['createdBy', 'jobImages'])
                ->latest();

            if ($request['posted_date'] == 'this_week') {
                $firstday = date('Y-m-d', strtotime("this week"));
                $lastday = date('Y-m-d', strtotime($firstday . ' + 6 days'));
                $list->whereBetween('created_at', [$firstday, $lastday]);
            }
            if ($request['posted_date'] == 'last_week') {
                $firstday = date('Y-m-d', strtotime("last week"));
                $lastday = date('Y-m-d', strtotime($firstday . ' + 6 days'));
                $list->whereBetween('created_at', [$firstday, $lastday]);
            }
            if ($request['posted_date'] == 'this_month') {
                $firstday =  date("Y-m-d", strtotime("first day of this month"));
                $lastday =  date("Y-m-d", strtotime("last day of this month"));
                $list->whereBetween('created_at', [$firstday, $lastday]);
            }
            if ($request['posted_date'] == 'last_month') {
                $firstday =  date("Y-m-d", strtotime("first day of last month"));
                $lastday =  date("Y-m-d", strtotime("last day of last month"));
                $list->whereBetween('created_at', [$firstday, $lastday]);
            }

            if (!empty($request['min_budget'])) {
                $list->where('fees_amount', '>=', $request['min_budget']);
            }

            if (!empty($request['max_budget'])) {
                $list->where('fees_amount', '<=', $request['max_budget']);
            }
            if (!empty($request['with_photo'])) {
                $list->whereHas('jobImages', function ($q) use ($request) {
                    $q->where('media_type', '=', 'image');
                });
            }
            if (!empty($request['search_job'])) {
                $title = $request['search_job'];
                $list->where('title', 'like', '%' . $title . '%');
            }

            if (!empty($request['search_location'])) {
                $title = $request['search_location'];
                $list->where('address', 'like', '%' . $title . '%');
            }
            $list = $list->paginate(10);
            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function inviteAppraiser($email, $invitedTo, $job_id)
    {
        try {
            $post['email'] = $email;
            $post['invited_to'] = $invitedTo;
            $post['invited_from'] = Auth::user()->id;
            $post['job_id'] = $job_id;
            $invitationData =  $this->jobInvitation->where(['job_id' => $job_id, 'invited_to' => $invitedTo, 'invited_from' => Auth::user()->id])->first();
            if (empty($invitationData)) {
                $invitation = $this->jobInvitation->create($post);
                $user = $this->user->where(['id' => $invitedTo])->first();
                $emailData = array(
                    'email' => $email,
                    'name' => $user->name,
                );
                CreateInvite::dispatch($user, $emailData);
                $message = "Invitation sent sucsessfully.";
                return ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            } else {
                return  ['success' => false, 'message' => 'Invitation Already Sent.', 'data' => []];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get my contract list in appraiser side
     * @param type $request
     * @return string
     */
    public function getAppraiserContractList($request)
    {
        $post = $request->all();
        try {
            $list = $this->job->where('status', '!=', 'deleted')->where('job_state', '!=', 'completed')->with(['createdBy', 'jobBid', 'jobAssignment']);
            $list->where(function ($q) use ($post) {
                $q->whereHas('jobAssignment', function ($q) use ($post) {
                    $q->where('assigned_to', Auth::user()->id);
                    $q->whereIn('status', array('active'));
                });
                $q->whereHas('jobBid', function ($q) use ($post) {
                    $q->where('user_id', Auth::user()->id);
                });
            });
            if (!empty($post['q'])) {
                // $list->whereHas('createdBy', function($q)use($post) {
                //     $q->where('name', 'like', '%' . $post['q'] . '%');
                // });

                $list->where(function ($q) use ($post) {
                    $q->orWhereHas('createdBy', function ($q) use ($post) {
                        $q->where('name', 'like', '%' . $post['q'] . '%');
                    });
                    $q->orWhereHas('jobBid', function ($q) use ($post) {
                        $q->where('price', 'like', '%' . $post['q'] . '%');
                    });
                });
            }
            $list->orderBy('id', 'DESC');
            $list = $list->paginate(10);

            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get my contract list in appraiser side
     * @param type $request
     * @return string
     */
    public function getAppraiserContractAssignedUsers($request)
    {
        $post = $request->all();
        try {
            $list = $this->jobAssignment->where('assigned_by', Auth::user()->id)->whereHas('assignedTo', function ($q) {
                $q->whereIn('users.user_role', ['manager', 'employee']);
            });

            if (!empty($post['q']) || !empty($post['user_role']) || !empty($post['assign_date'])) {
                $list->whereHas('assignedTo', function ($q) use ($post) {
                    if ($post['q']) {
                        $searchParam = $post['q'];
                        $q->whereRaw("name like '%$searchParam%'");
                    }

                    if ($post['user_role']) {
                        $userRole = $post['user_role'];
                        $q->where("user_role", $userRole);
                    }
                });

                if ($post['assign_date'] == 'this_week') {
                    $from = date('Y-m-d', strtotime("this week"));
                    $to = date('Y-m-d', strtotime($from . ' + 6 days'));
                    $list->whereBetween('date', [$from, $to]);
                }
                if ($post['assign_date'] == 'next_week') {
                    $from = date('Y-m-d', strtotime('monday next week'));
                    $to = date('Y-m-d', strtotime('sunday next week'));
                    $list->whereBetween('date', [$from, $to]);
                }
                if ($post['assign_date'] == 'this_month') {
                    $from =  date("Y-m-d", strtotime("first day of this month"));
                    $to =  date("Y-m-d", strtotime("last day of this month"));
                    $list->whereBetween('date', [$from, $to]);
                }
            }
            $list->orderBy('id', 'DESC');
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
    public function getAppraiserUserContractDetail($request)
    {
        try {

            $data = $this->job->where('jobs.id', $request->id)->where('jobs.status', '!=', 'deleted')->with(['createdBy', 'jobImages', 'jobDocs'])->first();
            $jobBidDetail = $this->jobBid->where('id', $request->jobBidId)->first();
            $data->jobBid = $jobBidDetail;
            return $data;
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
    public function getAppraiserContractDetail($request)
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

    /**
     * Assign Job to other user
     * @param type $request
     * @return string
     */
    public function appraiserAssignJob($request)
    {
        $post = $request->all();
        try {
            $jobAssinmentDetail = $this->jobAssignment->where('job_id', $post['job_id'])->where('assigned_to', $post['user_id'])->where('date', $post['date'])->first();
            if (empty($jobAssinmentDetail)) {
                $jobBidData =  $this->jobBid->where(['job_id' => $post['job_id']])->first();
                $data = [];
                $data['assigned_to'] = $post['user_id'];
                $data['job_id']      = $post['job_id'];
                $data['job_bid_id']  = $jobBidData->id;
                $data['date']        = $post['date'];
                $data['assigned_by'] = Auth::user()->id;
                $data['company_id'] = $jobBidData->company_id;
                $this->jobAssignment->where('job_id', $post['job_id'])->where('job_bid_id', $jobBidData->id)
                    ->where('assigned_to', Auth::user()->id)->where('status', 'active')->update(['status' => 'inactive']);
                $this->jobAssignment->create($data);
                return  ['success' => true, 'message' => 'Job successfully assigned.', 'data' => []];
            } else {
                return  ['success' => false, 'message' => 'Unable to assign the job. because user is busy in this date.', 'data' => []];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * to get job list in appraiser side
     * @param type $request
     * @return string
     */
    public function getApraiserOffersList($request)
    {

        $post = $request->all();
        try {

            $jobIds = [];
            $allMybids = $this->jobInvitation->select('job_id')->where('invited_to', Auth::user()->id)->get()->toArray();
            foreach ($allMybids as $bid) {
                $jobIds[] = $bid['job_id'];
            }

            $list = $this->job->where(['status' => 'active'])->whereIn('id', $jobIds)->latest();
            if (!empty($post['posted_by'])) {
                $list->whereHas('createdBy', function ($q) use ($post) {
                    $q->where('user_role', '=', $post['posted_by']);
                });
            }
            if (!empty($post['due_date'])) {
                if ($post['due_date'] == 'this_week') {
                    $firstday = date('Y-m-d', strtotime("this week"));
                    $lastday = date('Y-m-d', strtotime($firstday . ' + 6 days'));
                    $list->whereBetween('due_date', [$firstday, $lastday]);
                }

                if ($post['due_date'] == 'this_month') {
                    $firstday =  date("Y-m-d", strtotime("first day of this month"));
                    $lastday =  date("Y-m-d", strtotime("last day of this month"));
                    $list->whereBetween('due_date', [$firstday, $lastday]);
                }

                if ($post['due_date'] == 'this_today') {
                    $list->where('due_date', date('Y-m-d'));
                }
            }
            if (!empty($post['posted_date'])) {

                if ($post['posted_date'] == 'this_week') {
                    $firstday = date('Y-m-d', strtotime("this week"));
                    $lastday = date('Y-m-d', strtotime($firstday . ' + 6 days'));
                    $list->whereBetween('created_at', [$firstday, $lastday]);
                }

                // if ($post['posted_date'] == 'next_week') {
                //     $from = date('Y-m-d', strtotime('monday next week'));
                //     $to = date('Y-m-d', strtotime('sunday next week'));
                //     $list->whereBetween('created_at', [$from, $to]);
                // }

                if ($post['posted_date'] == 'this_month') {
                    $firstday =  date("Y-m-d", strtotime("first day of this month"));
                    $lastday =  date("Y-m-d", strtotime("last day of this month"));
                    $list->whereBetween('created_at', [$firstday, $lastday]);
                }
            }

            if (!empty($post['min_budget'])) {
                $list->where('fees_amount', '>=', $post['min_budget']);
            }

            if (!empty($post['max_budget'])) {
                $list->where('fees_amount', '<=', $post['max_budget']);
            }

            if (!empty($post['with_photo'])) {
                $list->whereHas('jobImages', function ($q) use ($post) {
                    $q->where('media_type', '=', 'image');
                });
            }

            if (!empty($post['search_job'])) {
                $title = $post['search_job'];
                $list->where('title', 'like', '%' . $title . '%');
            }

            if (!empty($post['search_location'])) {
                $title = $post['search_location'];
                $list->where('address', 'like', '%' . $title . '%');
            }

            $list = $list->paginate(10);
            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get total earning list in appraiser side
     * @param type $request
     * @return string
     */
    public function getTotalEarningList($request)
    {
        $post = $request->all();
        try {
            $list = $this->jobBid->where(['status' => 'accepted'])->with('jobDetail', 'jobDetail.createdBy', 'jobBidUserDetail.managerDetails', 'jobBidUserDetail.employeeDetails', 'jobBidUserDetail')->latest();
            $list->whereHas('jobDetail', function ($q) use ($post) {
                $q->where('status', '!=', 'deleted')->where('job_state', '=', 'completed');
            });
            // $list->whereHas('jobBidUserDetail', function ($q) use ($post) {
            //     $q->where('user_role', '!=', 'individual');
            // });

            if (Auth::user()->user_role == "manager") {
                $list->where(function ($q) use ($request) {
                    $q->whereHas('jobBidUserDetail.managerDetails', function ($q) {
                        $q->where('id', '=', Auth::user()->managerDetails->id);
                    });
                    $q->orWhereHas('jobBidUserDetail.employeeDetails', function ($q) {
                        $q->where('manager_id', '=',  Auth::user()->managerDetails->id);
                    });
                });
                $list->whereHas('jobBidUserDetail', function ($q) use ($post) {
                    $q->where('user_role', '!=', 'individual');
                });
            } else if (Auth::user()->user_role == "company") {
                $list->whereHas('jobDetail.jobBidss', function ($q) {
                    $q->where('company_id', '=', Auth::user()->companyDetails->id);
                });
                $list->whereHas('jobBidUserDetail', function ($q) use ($post) {
                    $q->where('user_role', '!=', 'individual');
                });
            } else {
                $list->whereHas('jobBidUserDetail', function ($q) {
                    $q->where('user_id', '=', Auth::user()->id);
                });
            }
            if (!empty($post['search_title'])) {
                if (Auth::user()->user_role == "manager") {
                   $list->whereHas('jobDetail', function ($q) use ($post) {
                        $q->where('title', 'like', '%' . $post['search_title'] . '%')
                            ->orWhereHas('createdBy', function ($q) use ($post) {
                                $q->where('name', 'like', '%' . $post['search_title'] . '%');
                            });
                        $q->Orwhere('fees_amount', '=', $post['search_title']);
                    });
                } else if (Auth::user()->user_role == "company") {
                    $list->whereHas('jobDetail', function ($q) use ($post) {
                        $q->where('title', 'like', '%' . $post['search_title'] . '%')
                            ->orWhereHas('jobBidss', function ($q) use ($post) {
                                $q->WhereHas('jobBidUserDetail', function ($q) use ($post) {
                                    $q->where('name', 'like', '%' . $post['search_title'] . '%');
                                });
                            });
                        $q->Orwhere('fees_amount', '=', $post['search_title']);
                    });
                } else {
                }
            }
            // if (isset($post['payment_date']) && $post['payment_date']) {
            //     $list->where('created_at','>=' , date('Y-m-d', strtotime($post['payment_date'])).' 00:00:00' );
            //     $list->where('created_at','<=' , date('Y-m-d', strtotime($post['payment_date'])).' 23:59:59' );
            // }

            if (!empty($post['min_amount'])) {
                $list->whereHas('jobDetail', function ($q) use ($post) {
                    $q->where('fees_amount', '>=', $post['min_amount']);
                });
            }

            if (!empty($post['max_amount'])) {
                $list->whereHas('jobDetail', function ($q) use ($post) {
                    $q->where('fees_amount', '<=', $post['max_amount']);
                });
            }

            $list = $list->paginate(10);

            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * Get lender detail
     * @param type $request
     * @return string
     */
    public function aboutLenderDetail($id)
    {
        try {
            $fromDate = date('Y-m-d');
            $toDate = date('Y-m-d', strtotime('+2 days'));
            $lenderJobDetail = $this->job
                ->select(DB::raw(
                    "COUNT(id) AS posted_job, COUNT(CASE WHEN job_state = 'completed' THEN 1 ELSE null END) AS completed_job,
                                            COUNT(CASE WHEN job_state = 'in_progress' && due_date >='$fromDate' && due_date <='$toDate' THEN 1 ELSE null END) AS critical_job,
                                            SUM(CASE WHEN job_state = 'completed' THEN fees_amount ELSE 0 END) AS total_earning"
                ))->where('status', '!=', 'deleted')->where('created_by', $id)->first();
            $lenderDetail = $this->user->select("id", "name", "created_at")->where("id", $id)->first();
            $lenderJobDetail->lender_detail = $lenderDetail;
            return $lenderJobDetail;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * Get lender detail
     * @param type $request
     * @return string
     */
    public function aboutAppraiserDetail($id)
    {
        try {
            $fromDate = date('Y-m-d');
            $toDate = date('Y-m-d', strtotime('+2 days'));
            //Query for job detail
            $appraiserJobDetail = $this->job
                ->join('job_assignments AS ja', 'ja.job_id', '=', 'jobs.id')
                ->select(DB::raw(
                    " COUNT(CASE WHEN (jobs.job_state='completed' AND ja.status='active') THEN 1 ELSE null END) AS finished_job,
                                                 COUNT(CASE WHEN (jobs.job_state='in_progress' AND ja.status='active' AND jobs.due_date >='$fromDate' AND jobs.due_date <='$toDate') THEN 1 ELSE null END) AS critical_tasks"
                ))->where('jobs.status', '!=', 'deleted')->where('assigned_to', $id)->first();
            //Query for bid accepted
            $appraiserAcceptedJob =  $this->job->join('job_bids AS jb', 'jb.job_id', '=', 'jobs.id')
                ->select(DB::raw("COUNT(CASE WHEN (jb.status='accepted') THEN 1 ELSE null END) AS accepted_job"))
                ->where('jobs.status', '!=', 'deleted')->where('user_id', $id)->first();

            if (!empty($appraiserAcceptedJob)) {
                $appraiserJobDetail->accepted_job = $appraiserAcceptedJob->accepted_job;
            } else {
                $appraiserJobDetail->accepted_job = 0;
            }

            //Query for bid award not recieved
            $appraiserBidDetail =  $this->job
            ->join('job_bids AS jb', 'jb.job_id', '=', 'jobs.id')
            ->join('users', 'users.id', '=', 'jobs.created_by')
            ->select(DB::raw( " COUNT(CASE WHEN (jb.status = 'requested') THEN 1 ELSE null END) AS bid_not_received"))
            ->where('jobs.status', '!=', 'deleted')
            ->where('jobs.job_state', '!=', 'completed')
            ->where('jb.user_id', $id)->first();

          if (!empty($appraiserBidDetail)) {
                $appraiserJobDetail->bid_not_received = $appraiserBidDetail->bid_not_received;
            } else {
                $appraiserJobDetail->bid_not_received = 0;
            }
            return $appraiserJobDetail;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get Complete Contracts List
     * @param type $request
     * @return string
     */
    public function getLenderCompleteContractList($request)
    {
        $post = $request->all();
        try {
            $list = $this->job->where('status', '!=', 'deleted')->where('created_by', Auth::user()->id)->where('job_state', '=', 'completed')->with(['jobAssignments', 'jobBidss', 'jobAssignments.getAssignedUser'])->latest();
            $list->whereHas('jobAssignments', function ($q) {
                $q->where('status',  '=', 'active');
            });
            if (!empty($post['q'])) {
                $list->whereHas('createdBy', function ($q) use ($post) {
                    $q->where('name', 'like', '%' . $post['q'] . '%');
                });
            }

            if (!empty($post['min_budget'])) {
                $list->whereHas('jobBidss', function ($q) use ($post) {
                    $q->where('price', '>=', $post['min_budget']);
                });
            }
            if (!empty($post['max_budget'])) {
                $list->whereHas('jobBidss', function ($q) use ($post) {
                    $q->where('price', '<=', $post['max_budget']);
                });
            }
            if (!empty($post['completed_date'])) {
                $list->whereHas('jobBidss', function ($q) use ($post) {
                    $q->where('completion_date', '>=', date('Y-m-d', strtotime($post['completed_date'])) . ' 00:00:00');
                    $q->where('completion_date', '<=', date('Y-m-d', strtotime($post['completed_date'])) . ' 23:59:59');
                });
            }

            if (!empty($post['with_photo'])) {
                $list->where('is_media_uploaded', '=', 1);
            }

            if (!empty($post['search_title'])) {
                $list->where('title', 'like', '%' . $post['search_title'] . '%')
                    ->orWhereHas('jobAssignments.getAssignedUser', function ($q) use ($post) {

                        $q->where('name', 'like', '%' . $post['search_title'] . '%');
                    });
            }
            $list = $list->paginate(10);
            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get jobs list before two date from due date
     * @param
     * @return Array
     */
    public static function getJobsExpiry()
    {
        try {
            $currentday = date('Y-m-d');
            $BeforeTwoFromCurrentday = date('Y-m-d', strtotime($currentday . ' + 2 days'));
            $list = Job::where('status', '!=', 'deleted')->where('job_state', '!=', 'completed')->where('job_state', '=', 'pending')->where('created_by', Auth::user()->id)->whereBetween('due_date', [$currentday, $BeforeTwoFromCurrentday])->get()->toArray();
            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get company job detail
     * @param
     * @return Array
     */
    public static function getCompanyJobsAssignmentDetail()
    {
        try {
            $userData = Auth::user();
            $unAssignedCount = Job::where('jobs.status', '!=', 'deleted')->where('jobs.job_state', '!=', 'completed')
                ->join('job_assignments', 'jobs.id', '=', 'job_assignments.job_id')
                ->where('job_assignments.assigned_to', $userData->id)->where('job_assignments.status', 'active')->count();

            $assignedCount = JobAssignment::where('assigned_by', $userData->id)->whereHas('assignedTo', function ($q) {
                $q->whereIn('users.user_role', ['manager', 'employee']);
            })->where('status', 'active')->count();
            return array('unAssignedCount' => $unAssignedCount, 'assignedCount' => $assignedCount);
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get applied jobs list before two date from due date
     * @param
     * @return Array
     */
    public static function getJobsAppliedExpiry()
    {
        try {
            $currentday = date('Y-m-d');
            $BeforeTwoFromCurrentday = date('Y-m-d', strtotime($currentday . ' + 2 days'));
            $list = JobAssignment::where('status', '!=', 'deleted')->where('assigned_to', Auth::user()->id)->with('getJobDetail');
            $list->whereHas('getJobDetail', function ($q) use ($BeforeTwoFromCurrentday) {
                $q->where('due_date', $BeforeTwoFromCurrentday)
                    ->where('job_state', '!=', 'completed');
            });
            $list = $list->get()->toArray();
            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * Complete job status
     * @param $job_id
     * @return Array
     */
    public function completeJobStatus($request)
    {
        try {
            $response = [];
            $jobData =  $this->job->where(['id' => $request->job_id])->first();
            if ($jobData->job_state == 'in_progress') {
                $jobData->update(['job_state' => 'completed']);
                $this->jobBid->where('job_id', $request->job_id)->where('status', 'accepted')->update(['completion_date' => date("Y-m-d h:i:s")]);
                $response = ['success' => true, 'message' => 'Job successfully completed.', 'error' => [], 'data' => []];
            } else {
                $response = ['success' => false, 'message' => 'Unable to change the status. please try again.', 'error' => [], 'data' => []];
            }

            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public static function getAwardDeatil($id, $userId)
    {
        $list = JobBid::where('job_id', $id)->where('user_id', $userId)->where('status', 'accepted')->first();
        return $list;
    }

    /**
     * Load job list for manage users
     * @param $user Id
     * @return Array
     */
    public function getJobListByUserId($request)
    {
        $list = $this->job->where('status', '!=', 'deleted')->where('created_by', $request->id)->latest();
        $currentDate = date('Y-m-d');
        if ($request->list_type == 'active_job') {
            $list->where('job_state', '=',  'pending');
            $list->where('due_date', '>',  $currentDate);
            $list->orderBy('id', 'DESC');
        }

        if ($request->list_type == 'expired_job') {
            $list->where('job_state', '=',  'pending');
            $list->where('due_date', '<',  $currentDate);
        }

        if ($request->list_type == 'inprogress_job') {
            $list->where('job_state', '=',  'in_progress');
            $list->orderBy('created_at', 'DESC');
        }

        if ($request->list_type == 'completed_job') {
            $list->where('job_state', '=',  'completed');
            $list->with(['jobBidss' => function ($query) {
                $query->whereNotNull('completion_date');
                $query->orderBy('completion_date', 'desc');
            }]);
            // if (!empty($request['query'])) {
            //     $list->whereHas('jobBidss',function($q1) use($request){
            //          $q1->where('completion_date','>=' , date('Y-m-d', strtotime($request['query'])).' 00:00:00' );
            //          $q1->where('completion_date','<=' , date('Y-m-d', strtotime($request['query'])).' 23:59:59' );

            //     });
            // }
        }

        if (!empty($request['query'])) {
            $list->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request['query'] . '%')
                    ->orWhere('borrower_name', 'like', '%' . $request['query'] . '%')
                    ->orWhere('fees_amount', $request['query']);
            });
        }

        $list = $list->paginate(10);
        return $list;
    }

    /**
     * To get appraiser current contracts List
     * @param type $request
     * @return string
     */
    public function getAppraiserCurrentContractList($request)
    {
        try {
            $post = $request->all();
            $list = $this->job->where('status', '!=', 'deleted')->where('job_state', '=', 'in_progress')->with(['jobAssignments', 'createdBy', 'jobAssignments.getAssignedUser']);
            $list->whereHas('jobAssignments', function ($q) use ($post) {
                $q->where('status',  '=', 'active')
                    ->where('assigned_to', $post['id']);
            });
            if (!empty($post['search_Keyword'])) {
                $list->whereHas('createdBy', function ($q) use ($post) {
                    $q->where('name', 'like', '%' . $post['search_Keyword'] . '%');
                    $q->Orwhere('fees_amount', '=', $post['search_Keyword']);
                    $q->Orwhere('title', 'like', '%' . $post['search_Keyword'] . '%');
                });
            }
            $list = $list->paginate(10);
            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /**
     * To get appraiser past contracts List
     * @param type $request
     * @return string
     */
    public function getApraiserPastContractList($request)
    {
        try {
            $post = $request->all();
            $list = $this->job->where('status', '!=', 'deleted')->where('job_state', '=', 'completed')->with(['jobAssignments', 'createdBy', 'jobAssignments.getAssignedUser', 'jobBidss']);
            $list->whereHas('jobAssignments', function ($q) use ($post) {
                $q->where('status',  '=', 'active')
                    ->where('assigned_to', $post['id']);
            });
            $list->whereHas('jobBidss', function ($q) use ($post) {
                $q->where('user_id', $post['id'])
                    ->where('status', 'accepted');
            });
            if (!empty($post['search_Keyword'])) {
                $list->whereHas('createdBy', function ($q) use ($post) {
                    $q->where('name', 'like', '%' . $post['search_Keyword'] . '%');
                    $q->Orwhere('fees_amount', '=', $post['search_Keyword']);
                    $q->Orwhere('title', 'like', '%' . $post['search_Keyword'] . '%');
                });
            }
            $list = $list->paginate(10);
            return $list;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function aboutJobBidDetail($jobId)
    {
        try {
            $bidData = $this->jobBid->select(
                DB::raw("ROUND(IFNULL(AVG(price),0),2) as avg_bid_amount"),
                DB::raw("COUNT(id) as total_bids")
            )->where('job_id', '=', $jobId)->first();

            return $bidData;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public static function deleteImageInThirtyMinuteByCron()
    {
        $deleteJob = Media::where('status', 'pending')->delete();
        exit();
    }
}
