<?php
namespace tzcs\modules\frontend\controllers;

use tzcs\library\FrontController;
use fay\core\Db;
use fay\core\Response;
use fay\core\Session;
class QueryController extends FrontController{
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
       $this->view->title = "体育成绩查询登陆";
       $this->view->render();
    }
    
    public function show(){
        $idNum = $this->input->post('idNum');
        $realName = $this->input->post('realName');
        
        if (empty($idNum) || empty($realName)){
            echo "<script>window.alert('输入不能为空！');window.history.back();</script>";
        }
        
        $db = Db::getInstance();
        $sql = sprintf("SELECT * FROM `fay_tzcs_students` WHERE `idNum` = %d AND `realName` = '%s'",$idNum, $realName);
        $result = $db->fetchRow($sql);
        
        if (!$result){
            Session::getInstance()->setFlash('error_message', '您输入的姓名或学号不正确，请检查重新输入！');
            Response::goback();
        }
        
        $student = json_decode($result['data']);
        
        $this->view->title = '体育成绩查询结果';
        $this->view->student = $student->data;
        $this->view->render();
        
    }
}