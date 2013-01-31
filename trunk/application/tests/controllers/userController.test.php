<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 25/1/13
 * Time: 1:29 AM
 * To change this template use File | Settings | File Templates.
 */
class UserControllerTest extends PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        UserControllerTest::loadSession();
    }

    protected static function loadSession()
    {
        \Session::started() or \Session::load();
    }

    public function setUp()
    {
        $this->markTestSkipped("Skipping User Controller");
    }

    public function testCreateUser()
    {
        $data = array(
            'email' => 'naveensky@gmail.com',
            'password' => 'asdf',
            'branchIds' => array(1)
        );

        Input::$json = (object)$data;

        Request::setMethod('POST');
        $response = Controller::call('user@post_create');
        $this->assertNotNull($response);
        $this->assertEquals(200, $response->status());

    }


}
