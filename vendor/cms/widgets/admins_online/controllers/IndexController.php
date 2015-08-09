<?php
namespace cms\widgets\admins_online\controllers;

use fay\core\Widget;
use fay\core\Sql;

class IndexController extends Widget{
	
	public function index($options){
		//在线管理员数
		$sql = new Sql();
		$this->view->admins = $sql->from('users', 'u', 'id,username,avatar,nickname')
			->joinLeft('user_profile', 'up', 'u.id = up.user_id', 'last_login_time,last_login_ip')
			->joinLeft('users_roles', 'ur', 'u.id = ur.user_id')
			->where(array(
				'up.last_time_online > '.(\F::app()->current_time - 60),
				'u.parent = 0',
			))
			->group('u.id')
			->fetchAll();
		$this->view->render();
	}
	
	public function placeholder(){
		
		$this->view->render('placeholder');
	}
}