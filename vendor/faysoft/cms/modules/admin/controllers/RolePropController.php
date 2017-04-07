<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\RolesTable;
use fay\helpers\HtmlHelper;
use fay\models\tables\PropsTable;
use fay\models\tables\ActionlogsTable;
use fay\services\user\UserPropService;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Response;
use fay\core\HttpException;

class RolePropController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'role';
	}
	
	public function index(){
		$role_id = $this->input->get('role_id', 'intval');
		
		$role = RolesTable::model()->fetchRow(array(
			'id = ?'=>$role_id,
			'delete_time = 0',
		));
		if(!$role){
			throw new HttpException('所选角色不存在');
		}
		
		$this->form()->setModel(PropsTable::model())
			->setData(array(
				'refer'=>$role_id,
			));
		$this->layout->subtitle = '角色属性 - '.HtmlHelper::encode($role['title']);
		$this->layout->sublink = array(
			'text'=>'返回角色列表',
			'uri'=>array('cms/admin/role/index'),
		);
		
		$this->_setListview($role_id);
		$this->view->render();
	}
	
	public function create(){
		if(!$this->input->post()){
			throw new HttpException('无数据提交', 500);
		}
		
		if($this->form()->setModel(PropsTable::model())->check()){
			$refer = $this->input->post('refer', 'intval');
			$prop = PropsTable::model()->fillData($this->input->post());
			$values = $this->input->post('prop_values', array());
			$prop_id = UserPropService::service()->create($refer, $prop, $values);
			
			$this->actionlog(ActionlogsTable::TYPE_ROLE_PROP, '添加了一个角色属性', $prop_id);
	
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
		
		$this->form()->setModel(PropsTable::model());
		if($this->input->post() && $this->form()->check()){
			$refer = $this->input->post('refer', 'intval');
			$prop = $this->form()->getFilteredData();
			isset($prop['required']) || $prop['required'] = 0;
			isset($prop['is_show']) || $prop['is_show'] = 0;
				
			$prop_values = $this->input->post('prop_values', array());
			$ids = $this->input->post('ids', 'intval', array('-1'));
				
			UserPropService::service()->update($refer, $prop_id, $prop, $prop_values, $ids);
			
			$this->actionlog(ActionlogsTable::TYPE_ROLE_PROP, '编辑了角色属性信息', $prop_id);
			
			Response::notify('success', '角色属性编辑成功', false);
		}
		
		
		$prop = UserPropService::service()->get($prop_id);

		if(!$prop){
			throw new HttpException('所选角色属性不存在');
		}
		$this->form()->setData($prop);
		
		$this->view->prop = $prop;

		$this->layout->sublink = array(
			'uri'=>array('cms/admin/role-prop/index', array('role_id'=>$prop['refer'])),
			'text'=>'添加角色属性',
		);
		
		//获取角色标题
		$role = RolesTable::model()->find($prop['refer'], 'title');
		$this->layout->subtitle = '编辑角色属性 - '.HtmlHelper::encode($role['title']).' - '.HtmlHelper::encode($prop['title']);

		$this->_setListview($prop['refer']);
		$this->view->render();
	}

	public function delete(){
		$id = $this->input->get('id', 'intval');
		$prop = PropsTable::model()->find($id, 'refer');
		UserPropService::service()->delete($id);

		Response::notify('success', array(
			'message'=>'删除了一个角色属性',
		), array('cms/admin/role-prop/index', array(
			'role_id'=>$prop['refer'],
		)));
	}

	public function sort(){
		$id = $this->input->get('id', 'intval');
		PropsTable::model()->update(array(
			'sort'=>$this->input->get('sort', 'intval'),
		), array(
			'id = ?'=>$id,
		));
		$this->actionlog(ActionlogsTable::TYPE_ROLE_PROP, '改变了角色属性排序', $id);
		
		$data = PropsTable::model()->find($id, 'sort');
		Response::notify('success', array(
			'message'=>'一个角色属性的排序值被编辑',
			'sort'=>$data['sort'],
		));
	}
	
	/**
	 * 设置右侧属性列表
	 * @param int $role_id
	 */
	private function _setListview($role_id){
		$sql = new Sql();
		$sql->from('props')
			->where(array(
				'delete_time = 0',
				'type = '.PropsTable::TYPE_ROLE,
				"refer = {$role_id}",
			))
			->order('sort');
		
		$this->view->listview = new ListView($sql, array(
			'page_size' => 15,
			'empty_text'=>'<tr><td colspan="5" align="center">无相关记录！</td></tr>',
		));
	}
}