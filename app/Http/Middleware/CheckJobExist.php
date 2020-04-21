<?php

namespace App\Http\Middleware;
use App\Models\Job;

use Closure;

class CheckJobExist
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
        $jobId = $request->job_id;
        $jobData = Job::where('id', $jobId)->first();
        if(!empty($jobData)){   
            return $next($request);
        }else{
           return response()->json(['success' => false, 'message' => 'Job not found.', 'error' => [], 'data' =>[]], 400);
        }
    }
}
