<?php namespace Tests;
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 22/1/13
 * Time: 8:01 PM
 * To change this template use File | Settings | File Templates.
 */
class BaseTest extends PHPUnit_Framework_TestCase
{

    protected function loadSession()
    {
        \Session::started() or \Session::load();
    }

    /*
	 Run the migrations in the test database
	*/
    protected function migrate()
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
}
