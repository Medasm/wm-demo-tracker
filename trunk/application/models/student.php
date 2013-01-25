<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 19/1/13
 * Time: 8:40 PM
 * To change this template use File | Settings | File Templates.
 */
class Student extends Eloquent
{
    public function demo()
    {
        return $this->has_one('Demo');
    }
}
