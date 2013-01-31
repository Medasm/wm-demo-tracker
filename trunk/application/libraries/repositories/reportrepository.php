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

        if (empty($status))
            return array();
        $statusString = "(";
        foreach ($status as $st) {
            $statusString = $statusString . "'" . $st . "',";
        }
        $statusString = rtrim($statusString, ",") . ")";
        $query = 'select * from ( SELECT demoStatus.demo_id,demoStatus.status,demoStatus.created_at  FROM demoStatus  JOIN demos ON demoStatus.demo_id=demos.id
    order by demoStatus.created_at desc) derived_table group by demo_id having status in ' . $statusString;

        $results = DB::query($query);
        if(empty($results))
            return array();
        $filteredDemoIds = array();
        foreach ($results as $result) {
            $filteredDemoIds[] = $result->demo_id;
        }
//        $filteredDemoIds = DB::table('demos')
//            ->join('demoStatus', 'demos.id', '=', 'demoStatus.demo_id')->order_by('demoStatus.created_at')->group_by('demoStatus.demo_id')->having_in('demoStatus.status', $status)
//            ->get('demos.id');
//        print_r($filteredDemoIds);
//        exit;
//        $filteredDemoIds = array(1);

        //todo: use loadbranch for loading branch along with demos
        return Demo::with(
            array('branch', 'demoStatus'))->
            where('demoDate', '>=', $fromDate)->
            where('demoDate', '<', $toDate)->
            where_in('id', $filteredDemoIds)->
            where_in('branch_id', $branchIds)->
            get();
    }

    public function getEnrolledDemos($branchIds)
    {
        return Demo::with(array('branch', 'demoStatus'))->
            where('demoDate', '>=', $fromDate)->
            where('demoDate', '<', $toDate)->
            where_in('branch_id', $branchIds)->
            get();
    }


}
