<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Roles;
use fay\helpers\Html;
use fay\models\tables\Props;
use fay\models\tables\Actionlogs;
use fay\models\Prop;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Response;
use fay\core\HttpException;
use fay\models\Flash;

class RolePropController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'role';
	}
	
	public function index(){
		$role_id = $this->input->get('role_id', 'intval');
		
		$role = Roles::model()->fetchRow(array(
			'id = ?'=>$role_id,
			'deleted = 0',
		));
		if(!$role){
			throw new HttpException('所选角色不存在');
		}
		
		$this->form()->setModel(Props::model())
			->setData(array(
				'refer'=>$role_id,
			));
		$this->layout->subtitle = '角色属性 - '.Html::encode($role['title']);
		$this->layout->sublink = array(
			'text'=>'返回角色列表',
			'uri'=>array('admin/role/index'),
		);
		
		$this->_setListview($role_id);
		$this->view->render();
	}
	
	public function create(){
		if(!$this->input->post()){
			throw new HttpException('无数据提交', 500);
		}
		
		if($this->form()->setModel(Props::model())->check()){
			$refer = $this->input->post('refer', 'intval');
			$prop = Props::model()->fillData($this->input->post());
			$values = $this->input->post('prop_values', array());
			$prop_id = Prop::model()->create($refer, Props::TYPE_ROLE, $prop, $values);
			
			$this->actionlog(Actionlogs::TYPE_ROLE_PROP, '添加了一个角色属性', $prop_id);
	
			Response::notify('success', array(
				'message'=>'角色属性添加成功',
				'id'=>$prop_id,
			));
		}else{
			//若表单验证出错，返回上一页
			Response::goback();
		}
	}
	
	public function edit(){
		$prop_id = $this->input->get('id', 'intval');
		
		$this->form()->setModel(Props::model());
		if($this->input->post() && $this->form()->check()){
			$refer = $this->input->post('refer', 'intval');
			$prop = $this->form()->getFilteredData();
			isset($prop['required']) || $prop['required'] = 0;
			isset($prop['is_show']) || $prop['is_show'] = 0;
				
			$prop_values = $this->input->post('prop_values', array());
			$ids = $this->input->post('ids', 'intval', array('-1'));
				
			Prop::model()->update($refer, $prop_id, $prop, $prop_values, $ids);
			
			Flash::set('角色属性编辑成功', 'success');
			$this->actionlog(Actionlogs::TYPE_ROLE_PROP, '编辑了角色属性信息', $prop_id);
		}
		
		
		$prop = Prop::model()->get($prop_id, Props::TYPE_ROLE);

		if(!$prop){
			throw new HttpException('所选角色属性不存在');
		}
		$this->form()->setData($prop);
		
		$this->view->prop = $prop;

		$this->layout->sublink = array(
			'uri'=>array('admin/role-prop/index', array('id'=>$prop['refer'])),
			'text'=>'添加角色属性',
		);
		
		//获取角色标题
		$role = Roles::model()->find($prop['refer'], 'title');
		$this->layout->subtitle = '编辑角色属性 - '.Html::encode($role['title']).' - '.Html::encode($prop['title']);

		$this->_setListview($prop['refer']);
		$this->view->render();
	}

	public function delete(){
		$id = $this->input->get('id', 'intval');
		$prop = Props::model()->find($id, 'refer');
		Prop::model()->delete($id);

		Response::notify('success', array(
			'message'=>'删除了一个角色属性',
		), array('admin/role-prop/index', array(
			'id'=>$prop['refer'],
		)));
	}

	public function sort(){
		$id = $this->input->get('id', 'intval');
		$result = Props::model()->update(array(
			'sort'=>$this->input->get('sort', 'intval'),
		), array(
			'id = ?'=>$id,
		));
		$this->actionlog(Actionlogs::TYPE_ROLE_PROP, '改变了角色属性排序', $id);
		
		$data = Props::model()->find($id, 'sort');
		Response::notify('success', array(
			'message'=>'一个角色属性的排序值被编辑',
			'sort'=>$data['sort'],
		));
	}
	
	/**
	 * 设置右侧属性列表
	 */
	private function _setListview($role_id){
		$sql = new Sql();
		$sql->from('props')
			->where(array(
				'deleted = 0',
				'type = '.Props::TYPE_ROLE,
				"refer = {$role_id}",
			))
			->order('sort');
		
		$this->view->listview = new ListView($sql, array(
			'page_size' => 15,
			'empty_text'=>'<tr><td colspan="5" align="center">无相关记录！</td></tr>',
		));
	}
}