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

        $statusString = "";
        foreach ($status as $st) {
            $statusString = $statusString . "'" . $st . "',";
        }

        $statusString = rtrim($statusString, ",");

        $query = "
        SELECT demo_id from (
            SELECT
            ds.demo_id,
            ds.status,
            ROW_NUMBER() OVER (PARTITION BY ds.demo_id ORDER BY ds.created_at DESC) as rownum
            from \"demoStatus\" ds) as filteredTable where rownum = 1 AND status in ($statusString)
        ";


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

        $query = "
        SELECT demo_id from (
            SELECT
            ds.demo_id,
            ds.status,
            ROW_NUMBER() OVER (PARTITION BY ds.demo_id ORDER BY ds.created_at DESC) as rownum
            from \"demoStatus\" ds) as filteredTable where rownum = 1 AND status in ('follow_up')
        ";

        $results = DB::query($query);

        if (empty($results))
            return array();

        $filteredDemoIds = array();

        foreach ($results as $result) {
            $filteredDemoIds[] = $result->demo_id;
        }

        $query;

        if (empty($date)) {
            //todo: use load branch for loading branch along with demos
            $query = Demo::with(array('branch', 'demoStatus'))->
                where_in('id', $filteredDemoIds)->
                where_in('branch_id', $branchIds);
        } else {

            $dateValue = date('Y', $date->getTimestamp()) . "-" . date('m', $date->getTimestamp()) . "-" . date('d', $date->getTimestamp()) . " 00:00:00";

            $fromDate = new DateTime($dateValue);
            $toDate = new DateTime($dateValue);
            $toDate->add(new DateInterval('P1D'));

            $branchIdsString = implode(",", $branchIds);
            $demoIdsString = implode(",", $filteredDemoIds);

            $query = "
                SELECT d.id FROM \"demos\" d
                    JOIN \"demoStatus\" ds on d.id = ds.demo_id
                    where
                    ds.\"followupDate\" >= ? AND ds.\"followupDate\" < ? AND
                    d.branch_id in ($branchIdsString) AND
                    d.id in ($demoIdsString)";

            $results = DB::query($query, array($fromDate, $toDate));

            if (empty($results))
                return array();

            $demoIds = array();

            foreach ($results as $result) {
                $demoIds[] = $result->id;
            }

            $query = Demo::with(array('branch', 'demoStatus'))->
                where_in('id', $demoIds);
        }

        return $query->get();
    }
}
