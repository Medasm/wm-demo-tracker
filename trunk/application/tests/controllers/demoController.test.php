<?php

/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 20/1/13
 * Time: 3:47 PM
 * To change this template use File | Settings | File Templates.
 */
class DemoControllerTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        DemoControllerTest::loadSession();
//        DemoControllerTest::migrate();
    }

    public function setUp()
    {
//        $this->markTestSkipped("Skipping Demo Controller");
    }

    public function testCanCreateDemo()
    {
        $this->markTestSkipped("Skipping Demo Controller");
        $userId = $this->getUser()->id;
        $branchId = $this->getBranch()->id;

        $data = array(
            'branchId' => $branchId,
            'studentName' => 'student name',
            'mobile' => '9891112345',
            'demoDate' => '2012-01-12 05:00:00',
            'course' => 'gmat-verbal',
            'faculty' => 'teacher',
        );

        Input::$json = (object)$data;

        //make the first user as logged in user
        Auth::login($userId);

        Request::setMethod('POST');
        $response = Controller::call('demo@post_add');
        $this->assertNotNull($response);
        $this->assertEquals(200, $response->status());
    }


    public function testListDemos()
    {
        $this->markTestSkipped("Skipping Demo Controller");
        $response = Controller::call('demo@list');
        $this->assertNotNull($response);
        $this->assertEquals(200, $response->status());
    }

    public function testPostListDemo(){
        $data = array(
            'demoId' => 2,
            'branchIds' =>array(1),
            'status' =>array('enrolled'),
            'demoDate' => '21 Jan 2013'
        );

        //forced logged in for a user to get pass through authentication
        Auth::login(3);

        Input::$json = (object)$data;

        Request::setMethod('POST');
        $response = Controller::call('demo@post_list');
        $this->assertNotNull($response);
        $this->assertEquals(200, $response->status());
        var_dump($response->content);
    }

    public function testCreateFollowup()
    {
        $this->markTestSkipped("Skipping Demo Controller");
        $demoId = $this->getDemo()->id;

        $data = array(
            'demoId' => $demoId,
            'remarks' => 'test remarks',
            'followupDate' => '2012-01-12 05:00:00',
        );

        Input::$json = (object)$data;

        Request::setMethod('POST');
        $response = Controller::call('demo@mark_followup');
        $this->assertNotNull($response);
        $this->assertEquals(200, $response->status());
    }

    public function testEnrollStudent()
    {
        $this->markTestSkipped("Skipping Demo Controller");
        $demoId = $this->getDemo()->id;

        $data = array(
            'demoId' => $demoId,
            'remarks' => 'test remarks',
            'joiningDate' => '2012-01-12 05:00:00',
        );

        Input::$json = (object)$data;

        Request::setMethod('POST');
        $response = Controller::call('demo@mark_enrolled');
        $this->assertNotNull($response);
        $this->assertEquals(200, $response->status());
    }

    public function testMarkAbsent()
    {
        $this->markTestSkipped("Skipping Demo Controller");
        $demoId = $this->getDemo()->id;

        $data = array(
            'demoId' => $demoId,
        );

        Input::$json = (object)$data;

        Request::setMethod('POST');
        $response = Controller::call('demo@mark_absent');
        $this->assertNotNull($response);
        $this->assertEquals(200, $response->status());
    }

    public function testMarkNotInterested()
    {

        $this->markTestSkipped("Skipping Demo Controller");
        $demoId = $this->getDemo()->id;

        $data = array(
            'demoId' => $demoId,
            'remarks' => 'test remarks',
        );

        Input::$json = (object)$data;

        Request::setMethod('POST');
        $response = Controller::call('demo@mark_not_interested');
        $this->assertNotNull($response);
        $this->assertEquals(200, $response->status());
    }


    protected static function loadSession()
    {
        \Session::started() or \Session::load();
    }

    /*
	 Run the migrations in the test database
	*/
    protected static function migrate()
    {
        // If there is not a declaration that migrations have been run'd
        if (!isset($GLOBALS['migrated_test_database'])) {
            // Run migrations
            require path('sys') . 'cli/dependencies' . EXT;
            \Laravel\CLI\Command::run(array('migrate:install'));
            \Laravel\CLI\Command::run(array('migrate'));

            // Declare that migrations have been run'd
            $GLOBALS['migrated_test_database'] = true;
        }
    }


    protected function getUser()
    {
        $user = DB::table('users')->first();

        if (!empty($user))
            return $user;

        $id = DB::table('users')->insert_get_id(array(
            'email' => 'abc@yahoo.com'
        ));

        return DB::table('users')->find($id);
    }

    protected function getBranch()
    {
        $branch = DB::table('branches')->first();

        if (!empty($branch))
            return $branch;

        $id = DB::table('branches')->insert_get_id(array(
            'name' => 'hauz khas'
        ));

        return DB::table('branches')->find($id);
    }

    protected function getDemo()
    {
        $demo = DB::table('demos')->first();

        if (!empty($demo))
            return $demo;

        $id = DB::table('demos')->insert_get_id(array(
            'name' => 'sample',
            'mobile' => '9810112345',
            'demoDate' => '2012-01-01 10:00:00',
            'program' => 'GMAT - VERBAL',
            'faculty' => 'FAC 1',
            'user_id' => $this->getUser()->id,
            'branch_id' => $this->getBranch()->id,
        ));

        return DB::table('demos')->find($id);
    }
}
