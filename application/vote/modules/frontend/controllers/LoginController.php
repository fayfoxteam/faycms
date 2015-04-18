<?php
namespace vote\modules\frontend\controllers;

use fay\core\Response;
use fay\models\User;
use vote\library\FrontendController;

class LoginController extends FrontendController
{
    public function index()
    {
        if ($this->input->post())
        {
            if ($this->input->post('vcode') && ($this->input->post('vcode', 'strtolower') != $this->session->get('vcode')))
            {
                $this->finish(array('code' => 2, 'message' => '验证码不正确'));
            }
            $username = $this->input->post('username', 'trim');
            $password = $this->input->post('password', 'trim');
            $result = User::model()->userLogin($username, $password);
            if ($result['status'])
            {
                $this->finish(array('code' => 0));
            }
            else
            {
                $this->finish(array('code' => 1, 'message' => $result['message']));
            } 
        }
    }

    public function logout()
    {
        User::model()->logout();
        Response::redirect('index');
    }
}
