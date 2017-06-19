<?php
namespace cms\widgets\admin\ip_statistics\controllers;

use fay\core\Loader;
use fay\core\Sql;
use fay\widget\Widget;

class IndexController extends Widget{
    public function index(){
        //引入IP地址库
        Loader::vendor('IpLocation/IpLocation.class');
        $this->view->iplocation = new \IpLocation();
        
        $sql = new Sql();
        $this->view->ips = $sql->from(array('v'=>'analyst_visits'), 'ip_int,COUNT(*) AS count')
            ->where(array(
                'create_date = ?'=>date('Y-m-d'),
            ))
            ->group('ip_int')
            ->order('count DESC')
            ->limit(10)
            ->fetchAll()
        ;
        
        $this->view->render();
    }
    
    public function placeholder(){
        
        $this->view->render('placeholder');
    }
}