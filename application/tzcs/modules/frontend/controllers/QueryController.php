<?php
namespace tzcs\modules\frontend\controllers;

use tzcs\library\FrontController;
use fay\core\Db;
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
        
        $student = json_decode($result['data']);
        
        $this->view->title = '体育成绩查询结果';
        $this->view->student = $student->data;
        $this->view->render();
        
    }
}