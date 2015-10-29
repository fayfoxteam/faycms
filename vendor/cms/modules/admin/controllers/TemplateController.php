<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\Templates;
use fay\models\tables\Actionlogs;
use fay\core\Response;
use fay\helpers\Html;
use fay\models\Flash;

class TemplateController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'template';
	}
	
	public function index(){
		$this->layout->subtitle = '模板管理';
		$sql = new Sql();
		$sql->from('templates', 't')
			->where('deleted = 0')
			->order('id DESC')
		;
		$this->view->listview = new ListView($sql);
		
		$this->view->render();
	}
	
	public function delete(){
		$id = $this->input->get('id', 'intval');
		Templates::model()->update(array(
			'deleted'=>1,
		), $id);
		$this->actionlog(Actionlogs::TYPE_TEMPLATE, '删除模版', $id);
		
		Response::notify('success', array(
			'message'=>'一个模板被删除 - '.Html::link('撤销', array('admin/template/undelete', array(
				'id'=>$id,
			))),
		));
	}
	
	public function undelete(){
		$id = $this->input->get('id', 'intval');
		Templates::model()->update(array(
			'deleted'=>0,
		), $id);
		$this->actionlog(Actionlogs::TYPE_TEMPLATE, '还原模版', $id);
		
		Response::notify('success', array(
			'message'=>'一个模板被还原',
		));
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑模板';
		
		$this->layout->sublink = array(
			'uri'=>array('admin/template/create'),
			'text'=>'添加模版',
		);
		
		$id = $this->input->get('id', 'intval');
		
		$this->form()->setModel(Templates::model());
		if($this->input->post()){
			if($this->form()->check()){
				$data = $this->form()->getFilteredData();
				if($data['type'] == Templates::TYPE_SMS){
					$data['content'] = trim(strip_tags($data['content']));
				}
				Templates::model()->update($data, $id);
				
				Flash::set('一个模版被编辑', 'success');
				$this->actionlog(Actionlogs::TYPE_TEMPLATE, '编辑了一个模版', $id);
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$this->view->template = Templates::model()->find($id);
		$this->form()->setData($this->view->template);
		$this->view->render();
	}
	
	public function create(){
		$this->layout->subtitle = '添加模板';
		
		$this->layout->sublink = array(
			'uri'=>array('admin/template/index'),
			'text'=>'模版列表',
		);
		
		$this->form()->setModel(Templates::model());
		if($this->input->post()){
			if($this->form()->check()){
				$data = $this->form()->getFilteredData();
				if($data['type'] == Templates::TYPE_SMS){
					$data['content'] = trim(strip_tags($data['content']));
				}
				$data['create_time'] = $this->current_time;
				$id = Templates::model()->insert($data);
				
				$this->actionlog(Actionlogs::TYPE_TEMPLATE, '添加了一个模版', $id);
				Response::notify('success', '模版添加成功', array('admin/template/edit', array(
					'id'=>$id,
				)));
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$this->view->render();
	}
	
	public function isAliasNotExist(){
		if(Templates::model()->fetchRow(array(
			'alias = ?'=>$this->input->post('value', 'trim'),
			'id != ?'=>$this->input->request('id', 'intval', false),
		))){
			Response::json('', 0, '别名已存在');
		}else{
			Response::json();
		}
	}
	
}