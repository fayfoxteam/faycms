<?php
namespace siwi\library;

use fay\core\Uri;
use fay\core\Response;

class UserController extends FrontController{
    public $layout_template = 'default';
    /**
     * 当前用户id（users表中的ID）
     * @var int
     */
    public $current_user = 0;

    public function __construct(){
        parent::__construct();
        
        //验证session中是否有值
        if(!\F::session()->get('user.id')){
            Response::redirect('login', array(
                'redirect'=>base64_encode($this->view->url(Uri::getInstance()->router, $this->input->get())),
            ), false);
        }
        
        $this->current_user = \F::session()->get('user.id', 0);
    }
}