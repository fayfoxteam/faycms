<?php
namespace cms\widgets\ip_statistics\controllers;

use fay\widget\Widget;
use fay\core\Sql;
use fay\core\Loader;

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