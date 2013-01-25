<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 20/1/13
 * Time: 3:26 PM
 * To change this template use File | Settings | File Templates.
 */
class Constants
{
    const SERVER_ERROR_CODE = '500';
    const CLIENT_ERROR_CODE = '400';

    public static function getCourses()
    {

        return array(
            "GMAT - VERBAL",
            "GMAT - QUANT",
            "GMAT - REASONING",
            "GRE - VERBAL",
            "GRE - QUANT",
            "GRE - REASONING",
        );
    }

    public static function getFaculty()
    {
        return array(
            "Meeshu",
            "Lakshdeep",
            "Pankaj Jain",
            "Aditya Jain"
        );
    }

}
