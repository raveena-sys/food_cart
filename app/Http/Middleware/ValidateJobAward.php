<?php

namespace App\Http\Middleware;
use App\Models\JobBid;
use App\Models\Job;

use Closure;

class ValidateJobAward
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $jobData = Job::where('id', $request->job_id)->first();
        if(!empty($jobData)){

             $totalAcceptedBId = JobBid::where('job_id', $request->job_id)->where('status', 'accepted')->count();
             if($totalAcceptedBId < $jobData->no_of_required_appraiser){
                return $next($request);
             }else{
                return response()->json(['success' => false, 'message' => 'Invalid request. Job strength already completed.', 'error' => [], 'data' =>[]], 200);
             }

        }else{
           return response()->json(['success' => false, 'message' => 'Job not found.', 'error' => [], 'data' =>[]], 400);
        }

    }
}
