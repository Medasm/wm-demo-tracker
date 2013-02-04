<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 23/1/13
 * Time: 7:34 AM
 * To change this template use File | Settings | File Templates.
 */
class ReportRepository
{
    public function getDemosForDay(DateTime $date, $branchIds, $status, $loadBranch)
    {
        $dateValue = date('Y', $date->getTimestamp()) . "-" . date('m', $date->getTimestamp()) . "-" . date('d', $date->getTimestamp()) . " 00:00:00";

        $fromDate = new DateTime($dateValue);
        $toDate = new DateTime($dateValue);
        $toDate->add(new DateInterval('P1D'));

        //todo: clean this mess

        if (empty($status))
            return array();

        $statusString = "(";
        foreach ($status as $st) {
            $statusString = $statusString . "'" . $st . "',";
        }

        $statusString = rtrim($statusString, ",") . ")";

        $query = 'select * from (
                    SELECT demoStatus.demo_id,demoStatus.status,demoStatus.created_at
                    FROM demoStatus
                    JOIN demos ON demoStatus.demo_id=demos.id
                    order by demoStatus.created_at desc) derived_table group by demo_id having status in ' . $statusString;

        $results = DB::query($query);

        if (empty($results))
            return array();

        $filteredDemoIds = array();

        foreach ($results as $result) {
            $filteredDemoIds[] = $result->demo_id;
        }

        //todo: use loadbranch for loading branch along with demos
        return Demo::with(
            array('branch', 'demoStatus'))->
            where('demoDate', '>=', $fromDate)->
            where('demoDate', '<', $toDate)->
            where_in('id', $filteredDemoIds)->
            where_in('branch_id', $branchIds)->
            get();
    }

    public function getFollowUps(DateTime $date, $branchIds)
    {
        $dateValue = date('Y', $date->getTimestamp()) . "-" . date('m', $date->getTimestamp()) . "-" . date('d', $date->getTimestamp()) . " 00:00:00";

        $fromDate = new DateTime($dateValue);
        $toDate = new DateTime($dateValue);
        $toDate->add(new DateInterval('P1D'));

        //todo: clean this mess

        $query = "select * from (
                    SELECT demoStatus.demo_id,demoStatus.status,demoStatus.created_at
                    FROM demoStatus
                    JOIN demos ON demoStatus.demo_id=demos.id
                    order by demoStatus.created_at desc) derived_table group by demo_id having status = 'follow_up'";

        $results = DB::query($query);

        if (empty($results))
            return array();

        $filteredDemoIds = array();

        foreach ($results as $result) {
            $filteredDemoIds[] = $result->demo_id;
        }

        //todo: use load branch for loading branch along with demos
        return Demo::with(
            array('branch', 'demoStatus' => function ($query) use ($fromDate, $toDate) {
                $query->where('followupDate', '>=', $fromDate)->where('followupDate', '<', $toDate);
            }))->
            where_in('id', $filteredDemoIds)->
            where_in('branch_id', $branchIds)->
            get();
    }
}
