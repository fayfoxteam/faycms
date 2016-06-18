<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Options;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Response;
use fay\core\HttpException;
use fay\models\Flash;
use fay\models\tables\Actionlogs;

class OptionController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'site';
	}
	
	public function create(){
		if($this->input->post()){
			if($this->form()->setModel(Options::model())->check()){
				$data = $this->form()->getFilteredData();
				$data['create_time'] = $this->current_time;
				$data['last_modified_time'] = $this->current_time;
				$option_id = Options::model()->insert($data);
				
				$this->actionlog(Actionlogs::TYPE_OPTION, '添加了一个系统参数', $option_id);
				
				Response::notify('success', array(
					'message'=>'站点参数添加成功',
				));
			}else{
				Response::goback();
			}
		}else{
			Response::notify('error', array(
				'message'=>'不完整的请求',
			));
		}
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑参数';
		$this->layout->sublink = array(
			'uri'=>array('admin/option/index', array('page'=>$this->input->get('page', 'intval', 1))),
			'text'=>'添加参数',
		);
		$option_id = $this->input->get('id', 'intval');
		$this->form()->setModel(Options::model());
		if($this->input->post() && $this->form()->check()){
			$data = $this->form()->getFilteredData();
			$data['last_modified_time'] = $this->current_time;
			$result = Options::model()->update($data, array('id = ?'=>$option_id));
			
			$this->actionlog(Actionlogs::TYPE_OPTION, '编辑了一个系统参数', $option_id);
			Response::notify('success', '一个参数被编辑', false);
		}
		
		if($option = Options::model()->find($option_id)){
			$this->form()->setData($option);
			$this->view->option = $option;
			
			$this->_setListview();
			
			$this->view->render();
		}else{
			throw new HttpException('无效的ID');
		}
	}
	
	public function index(){
		Flash::set('这是一个汇总表，如果您不清楚它的含义，请不要随意修改，后果可能很严重！', 'warning');
		$this->layout->subtitle = '添加参数';
		
		$this->_setListview();
		
		$this->form()->setModel(Options::model());
		
		$this->view->render();
	}
	
	public function remove(){
		$option_id = $this->input->get('id', 'intval');
		
		if(!$option_id){
			Response::notify('error', '未指定参数ID');
		}
		
		$option = Options::model()->find($option_id);
		if(!$option){
			Response::notify('error', '指定参数ID不存在');
		}
		
		Options::model()->delete(array('id = ?'=>$option_id));
		
		$this->actionlog(Actionlogs::TYPE_OPTION, '移除了一个系统参数', $option['option_name']);
		
		Response::notify('success', array(
			'message'=>'一个参数被永久删除',
		), array('admin/option/index', $this->input->get()));
	}
	
	public function isOptionNotExist(){
		if(Options::model()->fetchRow(array(
			'option_name = ?'=>$this->input->request('option_name', 'trim'),
			'id != ?'=>$this->input->request('id', 'intval', 0),
		))){
			Response::json('', 0, '参数名已存在');
		}else{
			Response::json();
		}
	}
	
	/**
	 * 设置右侧列表
	 */
	private function _setListview(){
		$sql = new Sql();
		$sql->from('options');

		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
			$sql->order("{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('id DESC');
		}
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>15,
			'empty_text'=>'<tr><td colspan="3" align="center">无相关记录！</td></tr>',
		));
	}
}