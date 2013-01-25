<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 19/1/13
 * Time: 8:25 PM
 * To change this template use File | Settings | File Templates.
 */
class Demo extends Eloquent
{
    public function demoStatus()
    {
        return $this->has_many('DemoStatus');
    }

    public function branch()
    {
        return $this->belongs_to('Branch');
    }

    public function student()
    {
        return $this->belongs_to('Student');
    }

    public function createdBy()
    {
        return $this->belongs_to('User');
    }


}
