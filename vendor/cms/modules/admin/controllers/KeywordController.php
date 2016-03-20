<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Keywords;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Response;
use fay\core\HttpException;
use fay\models\Flash;

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
				$data = Keywords::model()->fillData($this->input->post());
				Keywords::model()->insert($data);
				Response::notify('success', '关键词添加成功', array('admin/keyword/index'));
			}else{
				Response::goback();
			}
		}else{
			Response::notify('error', '不完整的请求', array('admin/keyword/index'));
		}
	}
	
	public function remove(){
		Keywords::model()->delete($this->input->get('id', 'intval'));
		
		Response::notify('success', '一个关键词被永久删除', array('admin/keyword/index', $this->input->get()));
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑关键词';
		$this->layout->sublink = array(
			'uri'=>array('admin/keyword/index', $this->input->get()),
			'text'=>'添加关键词',
		);
		$keyword_id = $this->input->get('id', 'intval');
		
		$check = $this->form()->setModel(Keywords::model());
		
		if($this->input->post() && $this->form()->check()){
			$data = Keywords::model()->fillData($this->input->post());
			Keywords::model()->update($data, array('id = ?'=>$keyword_id));
			Flash::set('一个关键词被编辑', 'success');
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
			'keyword = ?'=>$this->input->request('keyword', 'trim'),
			'id != ?'=>$this->input->request('id', 'intval', false),
		))){
			Response::json('', 0, '关键词已存在');
		}else{
			Response::json();
		}
	}
	
	/**
	 * 设置右侧列表
	 */
	private function _setListview(){
		$sql = new Sql();
		$sql->from(array('k'=>'keywords'));
		
		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'asc' : 'desc';
			$sql->order("k.{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('k.id DESC');
		}
		
		$this->view->listview = new ListView($sql, array(
			'page_size' => 15,
			'empty_text'=>'<tr><td colspan="2" align="center">无相关记录！</td></tr>',
		));
	}
}