<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 23/1/13
 * Time: 7:37 PM
 * To change this template use File | Settings | File Templates.
 */
class ReportControllerTest extends PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        ReportControllerTest::loadSession();
    }

    public function setUp()
    {
        $this->markTestSkipped("Skipping Report Controller");
    }

    public function testGetDemosForDay()
    {
        //todo: add test data before running
        $data = array(
            'branchIds' => array(1, 2, 3),
            'demoDate' => '2012-01-21',
            ''
        );

        Input::$json = (object)$data;

        //make the first user as logged in user
        Auth::login(1);

        Request::setMethod('POST');
        $response = Controller::call('report@get_demos');
        $this->assertNotNull($response);
        $this->assertEquals(200, $response->status());

        //get decoded json data
        $data = json_decode($response->content);

        $this->assertTrue(count($data) > 0);
    }

    protected static function loadSession()
    {
        \Session::started() or \Session::load();
    }
}
