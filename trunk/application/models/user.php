<?php

class User extends Eloquent
{

    public function branches()
    {
        return $this->has_many_and_belongs_to('Branch')->order_by('name');
    }

    public function roles()
    {
        return $this->has_many_and_belongs_to('Role');
    }

    public function demos()
    {
        return $this->has_many('Demo');
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

}