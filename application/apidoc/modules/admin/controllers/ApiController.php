<?php
namespace apidoc\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;

class ApiController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'api';
	}
	
	public function index(){
		$this->layout->subtitle = 'API列表';
		
		$sql = new Sql();
		$sql->from(array('a'=>'apidoc_apis'))
			->order('id DESC');
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>$this->form('setting')->getData('page_size', 20),
		));
		
		$this->view->render();
	}
	
	public function create(){
		
		$this->view->render();
	}
}