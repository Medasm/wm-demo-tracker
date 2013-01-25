<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 23/1/13
 * Time: 7:59 AM
 * To change this template use File | Settings | File Templates.
 */
class Report_Controller extends Base_Controller
{

    private $reportRepo;

    public function __construct()
    {
        parent::__construct();
        $this->reportRepo = new ReportRepository();
    }

    public function action_get_demos()
    {
        $data = Input::json();

        $demoDate = empty($data) || !isset($data->demoDate) ? new DateTime() : new DateTime($data->demoDate);
        $branchIds = empty($data) || !isset($data->branchIds) ? array() : $data->branchIds;

        if ($branchIds == null) {
            foreach (Auth::user()->branches() as $branch)
                $branchIds[] = $branch->id;
        }

        $demos = $this->reportRepo->getDemosForDay($demoDate, $branchIds);

        return Response::Eloquent($demos);
    }

    public function action_enrolled()
    {
        return View::make('report.enrolled');
    }

    public function action_enroll_later()
    {
        return View::make('report.enroll_later');
    }

    public function action_absentees()
    {
        return View::make('report.absentees');
    }

    public function action_not_interested()
    {
        return View::make('report.not_interested');
    }

}
