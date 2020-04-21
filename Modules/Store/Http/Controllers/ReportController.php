<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Repositories\ReportRepository;
use App\Repositories\JobRepository;
use Auth;
use View;
use Response;

class ReportController extends Controller
{
    private $report, $job;

    public function __construct(ReportRepository $report, JobRepository $job)
    {
        $this->report = $report;
        $this->job = $job;
    }

    /**
     * Load total revenue index page.
     * @return Response
     */
    public function totalRevenueIndex()
    {
        return view('admin::manage-report.total-revenue.index');
    }

    /**
     * Load registered company index page.
     * @return Response
     */
    public function registeredCompaniesIndex()
    {
        return view('admin::manage-report.registered-companies.index');
    }

    /**
     * Load registered individuals index page.
     * @return Response
     */
    public function registeredIndividualsIndex()
    {
        return view('admin::manage-report.registered-individuals.index');
    }

    /**
     * Load total jobs index page.
     * @return Response
     */
    public function totalJobsIndex()
    {
        return view('admin::manage-report.total-jobs.index');
    }

    /**
     * Gel all registered company list.
     * @param string $userType
     * @return Response
     */
    public function getCompanies(Request $request)
    {
        try {
            $data = $this->report->getCompanies($request);
            return $data;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Download appraiser company list Excel
     * @param string $userType
     * @return Response
     */
    public function exportCompanyCsv(Request $request)
    {
        try {
            $data = $this->report->exportCompanyCsv($request);
            return $data;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Gel all registered individual list.
     * @param string $userType
     * @return Response
     */
    public function getIndividuals(Request $request)
    {
        try {
            $data = $this->report->getIndividuals($request);
            return $data;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Download appraiser individual list Excel
     * @param string $userType
     * @return Response
     */
    public function exportIndividualsCsv(Request $request)
    {
        try {
            $data = $this->report->exportIndividualsCsv($request);
            return $data;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Gel all job list.
     * @param string $userType
     * @return Response
     */
    public function getTotalJobs(Request $request)
    {
        try {
            $data = $this->report->getTotalJobs($request);
            return $data;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Download individual list CSV
     * @param string $userType
     * @return Response
     */
    public function exportJobCsv(Request $request)
    {
        try {
            $data = $this->report->exportJobCsv($request);
            return $data;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Gel all total revenues.
     * @param string $userType
     * @return Response
     */
    public function getTotalRevenueReport(Request $request)
    {
        try {
            $data = $this->report->getTotalRevenueReport($request);
            return $data;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Download individual list CSV
     * @param string $userType
     * @return Response
     */
    public function exportTotalRevenueCsv(Request $request)
    {
        try {
            $data = $this->report->exportTotalRevenueCsv($request);
            return $data;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
