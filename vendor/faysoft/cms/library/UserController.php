<?php
namespace cms\library;

/**
 * 登录用户可访问的API
 */
class UserController extends ApiController{
    public function __construct(){
        parent::__construct();
        
        $this->checkLogin();
    }
}