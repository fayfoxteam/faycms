<?php
namespace apidoc\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use apidoc\models\tables\Outputs;

/**
 * 响应参数
 */
class OutputController extends AdminController{
	public function index(){
		$this->layout->subtitle = '添加响应参数';
		
		$this->_setListview();
		
		$this->form()->setModel(Outputs::model());
		
		$this->view->render();
	}
	
	public function create(){
		
	}
	
	public function edit(){
		
	}
	
	public function remove(){
		
	}
	
	/**
	 * 设置右侧列表
	 */
	private function _setListview(){
		$sql = new Sql();
		$sql->from('apidoc_outputs');

		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
			$sql->order("{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('id DESC');
		}
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>15,
			'empty_text'=>'<tr><td colspan="4" align="center">无相关记录！</td></tr>',
		));
	}
}