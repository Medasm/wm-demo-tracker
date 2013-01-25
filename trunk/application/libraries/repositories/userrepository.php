<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 23/1/13
 * Time: 7:23 AM
 * To change this template use File | Settings | File Templates.
 */
class UserRepository
{
    /**
     * @param $email
     * @param $password
     * @param $branchIds - array of branchIds to which user needs to be allocated
     * @return bool|User
     */
    public function createUser($email, $password, $branchIds)
    {
        $user = new User();
        $user->email = $email;
        $user->password = Hash::make($password);

        //todo: need to verify branchIds before sending them to add

        try {
            $user->save();
            $user->branches()->sync($branchIds);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }

        return $user;
    }

    public function getBranchesForUser($userId)
    {
        $user = User::find($userId);
        $branches = $user->branches()->get();

        $branchList = array();

        foreach ($branches as $branch) {
            $branchList[] = $branch;
        }

        return $branchList;
    }
}
