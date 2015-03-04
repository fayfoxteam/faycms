<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Keywords;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Response;
use fay\core\HttpException;

class KeywordController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'post';
	}
	
	public function index(){
		$this->layout->subtitle = '关键词';
		
		$this->_setListview();
		
		$this->form()->setModel(Keywords::model());
		$this->view->render();
	}
	
	public function create(){
		if($this->input->post()){
			if($this->form()->setModel(Keywords::model())->check()){
				$data = Keywords::model()->setAttributes($this->input->post());
				Keywords::model()->insert($data);
				Response::output('success', '关键词添加成功', array('admin/keyword/index'));
			}else{
				Response::output('error', $this->showDataCheckError($this->form()->getErrors(), true));
			}
		}else{
			Response::output('error', '不完整的请求', array('admin/keyword/index'));
		}
	}
	
	public function remove(){
		Keywords::model()->delete($this->input->get('id', 'intval'));
		
		Response::output('success', '一个关键词被永久删除', array('admin/keyword/index', $this->input->get()));
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑关键词';
		$this->layout->sublink = array(
			'uri'=>array('admin/keyword/index', $this->input->get()),
			'text'=>'添加关键词',
		);
		$keyword_id = $this->input->get('id', 'intval');
		
		$check = $this->form()->setModel(Keywords::model());
		
		if($this->input->post()){
			if($this->form()->check()){
				$data = Keywords::model()->setAttributes($this->input->post());
				Keywords::model()->update($data, array('id = ?'=>$keyword_id));
				$this->flash->set('一个关键词被编辑', 'success');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		if($keyword = Keywords::model()->find($keyword_id)){
			$this->form()->setData($keyword);
			$this->view->keyword = $keyword;
			
			$this->_setListview();
			
			$this->view->render();
		}else{
			throw new HttpException('无效的ID', 500);
		}
	}
	
	public function isKeywordNotExist(){
		if(Keywords::model()->fetchRow(array(
			'keyword = ?'=>$this->input->post('value', 'trim'),
			'id != ?'=>$this->input->request('id', 'intval', false),
		))){
			echo json_encode(array('status'=>0, 'message'=>'关键词已存在'));
		}else{
			echo json_encode(array('status'=>1));
		}
	}
	
	/**
	 * 设置右侧列表
	 */
	private function _setListview(){
		$sql = new Sql();
		$sql->from('keywords', 'k');
		
		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'asc' : 'desc';
			$sql->order("k.{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('k.id DESC');
		}
		
		$this->view->listview = new ListView($sql, array(
			'pageSize' => 15,
		));
	}
}