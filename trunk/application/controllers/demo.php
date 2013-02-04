<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 20/1/13
 * Time: 3:16 PM
 * To change this template use File | Settings | File Templates.
 */
class Demo_Controller extends Base_Controller
{
    private $demoRepo;
    private $userRepo;
    private $reportRepo;

    public function __construct()
    {
        parent::__construct();
        $this->demoRepo = new DemoRepository();
        $this->userRepo = new UserRepository();
        $this->reportRepo = new ReportRepository();

        //proceed ahead only if authenticated
        $this->filter('before', 'auth');
    }

    public function action_index()
    {
        //todo: pending
    }

    public function action_export_data()
    {
        $data = Input::json();

        $demoDate = isset($data->demoDate) ? new DateTime($data->demoDate) : new DateTime();
        $status = isset($data->status) ? $data->status : array();
        $branchIds = array();

        if (isset($data->branchIds))
            $branchIds = $data->branchIds;
        else {
            $branches = $this->userRepo->getBranchesForUser(Auth::user()->id);
            foreach ($branches as $branch) {
                $branchIds[] = $branch->id;
            }
        }

        $demos = $this->reportRepo->getDemosForDay($demoDate,
            $branchIds,
            $status,
            true);

        $csvData = AppHelper::ConvertDemosToCSV($demos);

        $filePath = AppHelper::generateTempFilePath("csv");

        File::put(AppHelper::convertToAbsoluteURL($filePath), $csvData);
        return AppHelper::convertToHttpURL($filePath);
    }

    public function action_export_data_followup()
    {
        $data = Input::json();

        $demoDate = isset($data->demoDate) ? new DateTime($data->demoDate) : new DateTime();
        $branchIds = array();

        if (isset($data->branchIds))
            $branchIds = $data->branchIds;
        else {
            $branches = $this->userRepo->getBranchesForUser(Auth::user()->id);
            foreach ($branches as $branch) {
                $branchIds[] = $branch->id;
            }
        }

        $demos = $this->reportRepo->getFollowUps($demoDate, $branchIds);

        if (empty($demos))
            return false;

        $csvData = AppHelper::ConvertDemosToCSV($demos);

        $filePath = AppHelper::generateTempFilePath("csv");

        File::put(AppHelper::convertToAbsoluteURL($filePath), $csvData);
        return AppHelper::convertToHttpURL($filePath);
    }


    /**
     * Get view for viewing list of demo for authenticated user
     * @return Laravel\View
     */
    public function action_list()
    {
        $demoDate = new DateTime();
        $branches = $this->userRepo->getBranchesForUser(Auth::user()->id);
        foreach ($branches as $branch) {
            $branchIds[] = $branch->id;
        }

        $demos = $this->reportRepo->getDemosForDay(
            $demoDate,
            $branchIds,
            array(DemoStatus::CREATED, DemoStatus::ENROLLED, DemoStatus::ABSENT, DemoStatus::NOT_INTERESTED, DemoStatus::FOLLOW_UP),
            true);

        return View::make('demo.list')->
            with('demoDate', $demoDate)->
            with('branches', $branches)->
            with('demos', $demos);
    }

    public function action_post_list()
    {
        $data = Input::json();

        $demoDate = isset($data->demoDate) ? new DateTime($data->demoDate) : new DateTime();
        $status = isset($data->status) ? $data->status : array();
        $branchIds = array();

        if (isset($data->branchIds))
            $branchIds = $data->branchIds;
        else {
            $branches = $this->userRepo->getBranchesForUser(Auth::user()->id);
            foreach ($branches as $branch) {
                $branchIds[] = $branch->id;
            }
        }

        $demos = $this->reportRepo->getDemosForDay($demoDate,
            $branchIds,
            $status,
            true);

        return Response::eloquent($demos);
    }


    public function action_follow_up()
    {
        $demoDate = new DateTime();
        $branches = $this->userRepo->getBranchesForUser(Auth::user()->id);

        return View::make('demo.follow_up')->
            with('demoDate', $demoDate)->
            with('branches', $branches);

    }

    public function action_post_follow_up()
    {
        $data = Input::json();

        $demoDate = isset($data->demoDate) ? new DateTime($data->demoDate) : new DateTime();
        $branchIds = array();

        if (isset($data->branchIds))
            $branchIds = $data->branchIds;
        else {
            $branches = $this->userRepo->getBranchesForUser(Auth::user()->id);
            foreach ($branches as $branch) {
                $branchIds[] = $branch->id;
            }
        }

        $demos = $this->reportRepo->getFollowUps($demoDate, $branchIds);

        return Response::eloquent($demos);
    }


    public function action_add()
    {
        $branches = $this->userRepo->getBranchesForUser(Auth::user()->id);
        $date = new DateTime();
        $courses = Constants::getCourses();
        $faculty = Constants::getFaculty();

        return View::make('demo.add')->
            with('branches', $branches)->
            with('date', $date)->
            with('courses', $courses)->
            with('faculty', $faculty);
    }

    /**
     * Post action for creating a demo
     *
     * @param int $branchId
     * @param string $studentName
     * @param string $mobile
     * @param datetime $demoDate
     * @param string $course
     * @param string $faculty
     *
     * @return Laravel\Response
     */
    public function action_post_add()
    {
        $data = Input::json();

        if (empty($data))
            return Response::error(Constants::CLIENT_ERROR_CODE, array(__('controller.missing_param')));

        //todo: server side validation for required and content validation

        $branchId = isset($data->branchId) ? $data->branchId : null;
        $studentName = isset($data->studentName) ? $data->studentName : null;
        $mobile = isset($data->mobile) ? $data->mobile : null;
        $demoDate = isset($data->demoDate) ? new DateTime($data->demoDate) : null;
        $course = isset($data->course) ? $data->course : null;
        $faculty = isset($data->faculty) ? $data->faculty : null;

        $demo = $this->demoRepo->createDemo($branchId, Auth::user()->id, $studentName, $mobile, $demoDate, $course, $faculty);

        if ($demo == false)
            return Response::error(Constants::SERVER_ERROR_CODE, array(__('controller.server_error')));

        return Response::eloquent($demo);
    }

    public function action_mark_enrolled()
    {
        $data = Input::json();

        if (empty($data))
            return Response::make(__('controller.missing_param'), Constants::CLIENT_ERROR_CODE);

        //todo: server side validation for required and content validation

        $demoId = isset($data->demoId) ? $data->demoId : null;
        $joiningDate = isset($data->joiningDate) ? new DateTime($data->joiningDate) : null;
        $remarks = isset($data->remarks) ? $data->remarks : null;

        $demo = $this->demoRepo->markDemoJoin($demoId, $joiningDate, $remarks);

        if ($demo == false)
            return Response::make(__('controller.server_error'), Constants::SERVER_ERROR_CODE);

        return Response::eloquent($demo);
    }

    public function action_mark_absent()
    {
        $data = Input::json();

        if (empty($data))
            return Response::make(__('controller.missing_param'), Constants::CLIENT_ERROR_CODE);

        //todo: server side validation for required and content validation

        $demoId = isset($data->demoId) ? $data->demoId : null;

        $demo = $this->demoRepo->markDemoAbsent($demoId);

        if ($demo == false)
            return Response::make(__('controller.server_error'), Constants::SERVER_ERROR_CODE);

        return Response::eloquent($demo);
    }

    public function action_mark_followup()
    {
        $data = Input::json();

        if (empty($data))
            return Response::make(__('controller.missing_param'), Constants::CLIENT_ERROR_CODE);

        //todo: server side validation for required and content validation

        $demoId = isset($data->demoId) ? $data->demoId : null;
        $followupDate = isset($data->followupDate) ? new DateTime($data->followupDate) : null;
        $remarks = isset($data->remarks) ? $data->remarks : null;

        $demoStatus = $this->demoRepo->createFollowup($demoId, $followupDate, $remarks);

        if ($demoStatus == false)
            return Response::make(__('controller.server_error'), Constants::SERVER_ERROR_CODE);

        return Response::eloquent($demoStatus);

    }

    public function action_mark_not_interested()
    {
        $data = Input::json();

        if (empty($data))
            return Response::make(__('controller.missing_param'), Constants::CLIENT_ERROR_CODE);

        //todo: server side validation for required and content validation

        $demoId = isset($data->demoId) ? $data->demoId : null;
        $remarks = isset($data->remarks) ? $data->remarks : null;

        $demoStatus = $this->demoRepo->markDemoNotInterested($demoId, $remarks);

        if ($demoStatus == false)
            return Response::make(__('controller.server_error'), Constants::SERVER_ERROR_CODE);

        return Response::eloquent($demoStatus);
    }

}
