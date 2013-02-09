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
    public function getDemosForDay(DateTime $date = null, $branchIds, $status, $loadBranch)
    {

        //todo: clean this mess
        if (empty($status))
            return array();

        $statusString = "(";
        foreach ($status as $st) {
            $statusString = $statusString . "'" . $st . "',";
        }

        $statusString = rtrim($statusString, ",") . ")";

        $query = 'select demo_id from (
                    SELECT "demoStatus".demo_id,"demoStatus".status,"demoStatus".created_at
                    FROM "demoStatus"
                    JOIN "demos" ON "demoStatus".demo_id=demos.id
                    order by "demoStatus".created_at desc) derived_table group by demo_id, status having status in ' . $statusString;

        $results = DB::query($query);

        if (empty($results))
            return array();

        $filteredDemoIds = array();

        foreach ($results as $result) {
            $filteredDemoIds[] = $result->demo_id;
        }

        //todo: use loadbranch for loading branch along with demos
        $query = Demo::with(
            array('branch', 'demoStatus'))->
            where_in('id', $filteredDemoIds)->
            where_in('branch_id', $branchIds);

        if (!empty($date)) {
            $dateValue = date('Y', $date->getTimestamp()) . "-" . date('m', $date->getTimestamp()) . "-" . date('d', $date->getTimestamp()) . " 00:00:00";

            $fromDate = new DateTime($dateValue);
            $toDate = new DateTime($dateValue);
            $toDate->add(new DateInterval('P1D'));

            $query = $query->
                where('demoDate', '>=', $fromDate)->
                where('demoDate', '<', $toDate);
        }

        return $query->get();
    }

    public function getFollowUps(DateTime $date = null, $branchIds)
    {
        //todo: clean this mess

        $query = 'select demo_id from (
                    SELECT "demoStatus".demo_id,"demoStatus".status,"demoStatus".created_at
                    FROM "demoStatus"
                    JOIN demos ON "demoStatus".demo_id=demos.id
                    order by "demoStatus".created_at desc) derived_table group by demo_id, status having status in (\'follow_up\')';

        $results = DB::query($query);

        if (empty($results))
            return array();

        $filteredDemoIds = array();

        foreach ($results as $result) {
            $filteredDemoIds[] = $result->demo_id;
        }


        if(empty($data))
            //todo: use load branch for loading branch along with demos
            return Demo::with(array('branch', 'demoStatus'))->
                where_in('id', $filteredDemoIds)->
                where_in('branch_id', $branchIds)->
                get();

        $dateValue = date('Y', $date->getTimestamp()) . "-" . date('m', $date->getTimestamp()) . "-" . date('d', $date->getTimestamp()) . " 00:00:00";

        $fromDate = new DateTime($dateValue);
        $toDate = new DateTime($dateValue);
        $toDate->add(new DateInterval('P1D'));

        return Demo::with(
            array('branch', 'demoStatus' => function ($query) use ($fromDate, $toDate) {
                $query->where('followupDate', '>=', $fromDate)->where('followupDate', '<', $toDate);
            }))->
            where_in('id', $filteredDemoIds)->
            where_in('branch_id', $branchIds)->
            get();
    }
}
