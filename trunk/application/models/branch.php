<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 19/1/13
 * Time: 8:12 PM
 * To change this template use File | Settings | File Templates.
 */
class Branch extends Eloquent
{
    public function users()
    {
        return $this->has_many_and_belongs_to('User');
    }

    public function demos()
    {
        return $this->has_many('Demo', 'branch_id');
    }
}
