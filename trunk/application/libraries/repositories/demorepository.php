<?php


class DemoRepository
{
    public function createDemo($branchId,
                               $userId,
                               $studentName,
                               $mobile,
                               DateTime $demoDate,
                               $course,
                               $faculty)
    {

        $branch = Branch::find($branchId);
        $user = User::find($userId);

        //if no branch or user is found for given, return false
        if (empty($branch) || empty($user))
            return false;

        $demo = new Demo();
        $demo->name = $studentName;
        $demo->mobile = $mobile;
        $demo->program = $course;
        $demo->faculty = $faculty;
        $demo->demoDate = $demoDate;
        $demo->Branch_id = $branchId;
        $demo->User_id = $userId;

        $demoStatus = new DemoStatus();
        $demoStatus->status = DemoStatus::CREATED;

        try {
            $demo->save();
            $demo->demoStatus()->insert($demoStatus);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }

        return $demo;
    }

    public function createFollowup($demoId, DateTime $followupDate, $remarks)
    {
        return $this->updateStatus($demoId, DemoStatus::FOLLOW_UP, $followupDate, null, $remarks);
    }

    public function markDemoAbsent($demoId)
    {
        return $this->updateStatus($demoId, DemoStatus::ABSENT);
    }

    public function markDemoJoin($demoId, $joiningDate, $remarks)
    {
        return $this->updateStatus($demoId, DemoStatus::ENROLLED, null, $joiningDate, $remarks);
    }

    public function markDemoNotInterested($demoId, $remarks)
    {
        return $this->updateStatus($demoId, DemoStatus::NOT_INTERESTED, null, null, $remarks);
    }

    private function updateStatus(
        $demoId, $status, $followupDate = null,
        $joiningDate = null, $remarks = null)
    {

        $demo = Demo::find($demoId);

        if (empty($demo))
            return false;

        $demoStatus = new DemoStatus();
        $demoStatus->status = $status;
        $demoStatus->followupDate = $followupDate;
        $demoStatus->joiningDate = $joiningDate;
        $demoStatus->remarks = $remarks;

        try {
            $demo->demoStatus()->insert($demoStatus);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return $demoStatus;
    }
}
