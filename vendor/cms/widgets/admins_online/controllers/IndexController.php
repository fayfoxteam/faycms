<?php
namespace cms\widgets\admins_online\controllers;

use fay\core\Widget;
use fay\models\tables\Users;

class IndexController extends Widget{
	
	public function index($options){
		//在线管理员数
		$sql = "SELECT
			u.id,
			u.realname,
			u.username,
			u.last_login_time,
			u.last_login_ip,
			r.title AS role_title
			FROM
			{$this->db->users} AS u
			LEFT JOIN {$this->db->roles} AS r ON u.role = r.id
			WHERE
			last_time_online > ".($this->current_time - 60)."
			AND
			role > ".Users::ROLE_SYSTEM."
			AND
			parent = 0
		";
		$this->view->admins = $this->db->fetchAll($sql);
		$this->view->render();
	}
	
	public function placeholder(){
		
		$this->view->render('placeholder');
	}
}