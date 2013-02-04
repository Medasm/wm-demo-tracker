<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 25/1/13
 * Time: 12:34 AM
 * To change this template use File | Settings | File Templates.
 */
class User_Controller extends Base_Controller
{

    private $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository();
    }

    public function action_login()
    {
        return View::make('user.login');
    }

    public function action_post_login()
    {
        $data = Input::json();

        if (empty($data))
            return Response::make(__("controller.missing_input"), Constants::CLIENT_ERROR_CODE);

        $credentials = array(
            'username' => $data->email,
            'password' => $data->password
        );

        if (Auth::attempt($credentials)) {
            return Response::json(
                array(
                    'status' => true,
                    'url' => URL::to('/')
                )
            );
        } else
            return Response::json(array(
                'status' => false
            ));
    }

    public function action_logout()
    {
        Auth::logout();
        return Redirect::home();
    }

    public function action_create()
    {
        return View::make('user.create');
    }

    public function action_post_create()
    {
        $data = Input::json();

        if (empty($data))
            return Response::make(__("controller.missing_input"), Constants::CLIENT_ERROR_CODE);

        $email = $data->email;
        $password = $data->password;
        $branchIds = $data->branchIds;

        $user = $this->userRepo->createUser($email, $password, $branchIds);

        if (empty($user))
            return Response::make(__("controller.server_error"), Constants::SERVER_ERROR_CODE);

        return Response::eloquent($user);
    }
}
