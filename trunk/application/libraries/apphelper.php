<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 2/2/13
 * Time: 12:56 PM
 * To change this template use File | Settings | File Templates.
 */
class AppHelper
{

    public static function ConvertDemosToCSV(array $demos)
    {
        $dataPoints = array();

        foreach ($demos as $demo) {
            $row = array();
            $row['name'] = $demo->name;
            $row['mobile'] = $demo->mobile;
            $row['demoDate'] = $demo->demoDate;
            $row['program'] = $demo->program;
            $row['faculty'] = $demo->faculty;
            $row['status'] = $demo->demoStatus[0]->status;
            $row['branch'] = $demo->branch->name;
            array_push($dataPoints, $row);
        }

        return AppHelper::ConvertToCSV($dataPoints);
    }

    /**
     * @param array $dataPoints - array of data points to convert to csv
     * @return string - csv record for data points
     */
    public static function ConvertToCSV(array $dataPoints)
    {
        $csvData = "";

        foreach ($dataPoints as $data) {
            $dataRow = "";
            foreach ($data as $key => $value) {
                $dataRow .= "\"$value\",";
            }

            $dataRow = rtrim($dataRow, ",");

            $csvData .= "$dataRow \n";
        }

        return $csvData;
    }

    public static function convertToAbsoluteURL($filePath)
    {
        return path('public') . ltrim($filePath, "/");
    }

    public static function convertToHttpURL($filePath)
    {
        return URL::base() . "/" . ltrim($filePath, "/");
    }

    public static function  generateTempFilePath($extension)
    {
        $extension = rtrim(ltrim($extension, "."), ".");
        $fileName = Str::random(64, 'alnum');
        return "tmp/$fileName.$extension";
    }
}


