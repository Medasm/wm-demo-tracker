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
    public function getDemosForDay(DateTime $date, $branchIds, $loadBranch)
    {

        $dateValue = date('Y', $date->getTimestamp()) . "-" . date('m', $date->getTimestamp()) . "-" . date('d', $date->getTimestamp()) . " 00:00:00";

        $fromDate = new DateTime($dateValue);
        $toDate = new DateTime($dateValue);
        $toDate->add(new DateInterval('P1D'));

        //todo: use loadbranch for loading branch along with demos
        return Demo::with(array('branch','demoStatus'))->
            where('demoDate', '>=', $fromDate)->
            where('demoDate', '<', $toDate)->
            where_in('branch_id', $branchIds)->
            get();
    }
}
