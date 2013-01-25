<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 19/1/13
 * Time: 8:26 PM
 * To change this template use File | Settings | File Templates.
 */
class DemoStatus extends Eloquent
{
    const CREATED = "created";
    const ABSENT = "absent";
    const ENROLLED = "enrolled";
    const NOT_INTERESTED = "not_interested";
    const FOLLOW_UP = "follow_up";

    public static $table = 'demoStatus';

    public function demo()
    {
        return $this->belongs_to('Demo');
    }
}
