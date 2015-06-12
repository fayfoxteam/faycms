<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\Widgetareas;
use fay\core\Response;
use fay\core\HttpException;
use fay\helpers\Html;

class WidgetareaController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'site';
	}
	
	public function index(){
		$this->layout->subtitle = '小工具域';
		
		$this->_setListview();
		
		$this->form()->setModel(Widgetareas::model());
		
		$this->view->render();
	}
	
	public function create(){
		if($this->input->post()){
			if($this->form()->setModel(Widgetareas::model())->check()){
				$data = $this->form()->getFilteredData();
				Widgetareas::model()->insert($data);
				
				Response::output('success', array(
					'message'=>'小工具域创建成功',
				), array('admin/widgetarea/index'));
			}else{
				Response::output('error', array(
					'message'=>'参数异常',
				), array('admin/option/index'));
			}
		}else{
			Response::output('error', array(
			'message'=>'不完整的请求',
			), array('admin/option/index'));
		}
	}
	
	public function edit(){
		$this->layout->subtitle = '小工具域';
		
		$widgetarea_id = $this->input->get('id', 'intval');
		$this->form()->setModel(Widgetareas::model());
		if($this->input->post()){
			if($this->form()->check()){
				$data = $this->form()->getFilteredData();
				Widgetareas::model()->update($data, array('id = ?'=>$widgetarea_id));
				$this->flash->set('一个小工具域被编辑', 'success');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		if($widgetarea = Widgetareas::model()->find($widgetarea_id)){
			$this->form()->setData($widgetarea);
				
			$this->_setListview();
			
			
				
			$this->view->render();
		}else{
			throw new HttpException('无效的ID');
		}
	}
	
	public function delete(){
		$id = $this->input->get('id', 'intval');
		Widgetareas::model()->update(array(
			'deleted'=>1,
		), $id);
		Response::output('success', '一个小工具域被删除。'.Html::link('撤销', array('admin/widgetarea/undelete', array(
			'id'=>$id,
		))));
	}
	
	public function undelete(){
		$id = $this->input->get('id', 'intval');
		Widgetareas::model()->update(array(
			'deleted'=>0,
		), $id);
		Response::output('success', '一个小工具域被还原');
	}
	
	public function isAliasNotExist(){
		if(Widgetareas::model()->fetchRow(array(
			'alias = ?'=>$this->input->post('value', 'trim'),
			'id != ?'=>$this->input->request('id', 'intval', false)
		))){
			echo json_encode(array(
				'status'=>0,
				'message'=>'别名已存在',
			));
		}else{
			echo json_encode(array(
				'status'=>1,
			));
		}
	}
	
	/**
	 * 设置右侧列表
	 */
	private function _setListview(){
		$sql = new Sql();
		$sql->from('widgetareas')
			->where('deleted = 0');

		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'asc' : 'desc';
			$sql->order("{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('id DESC');
		}
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>15,
			'empty_text'=>'<tr><td colspan="2" align="center">无相关记录！</td></tr>',
		));
	}
}