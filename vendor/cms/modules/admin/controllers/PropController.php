<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Categories;
use fay\helpers\Html;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\CatProps;
use fay\models\tables\CatPropValues;
use fay\models\tables\Actionlogs;
use fay\core\Response;
use fay\models\tables\Props;

/**
 * 商品属性
 */
class PropController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'goods';
	}
	
	public function index(){
		$cid = $this->input->get('cid', 'intval');
		$cat = Categories::model()->find($cid, 'id,title');
		$this->view->cat = $cat;

		$this->layout->subtitle = Html::encode($cat['title']) . ' - 分类属性';
		
		$sql = new Sql();
		$sql->from('cat_props')
			->where(array(
				'deleted = 0',
				'cat_id = ?'=>$cid,
			))
			->order('sort');
		$listview = new ListView($sql);
		$listview->pageSize = 15;
		$this->view->listview = $listview;
		
		$this->view->render();
	}
	
	public function create(){
		if($this->input->post()){
			$is_sale_prop = $this->input->post('is_sale_prop', 'intval', 0);
			if($is_sale_prop){
				$required = 1;
			}else{
				$required = $this->input->post('required', 'intval', 0);
			}
			$prop_id = CatProps::model()->insert(array(
				'cat_id'=>$this->input->post('cat_id', 'intval'),
				'type'=>$this->input->post('type', 'intval'),
				'required'=>$required,
				'title'=>$this->input->post('title'),
				'is_sale_prop'=>$is_sale_prop,
				'is_input_prop'=>$this->input->post('is_input_prop', 'intval', 0),
				'sort'=>$this->input->post('sort', 'intval', 0),
			));
			
			//设置属性值
			if($this->input->post('type', 'intval') != CatProps::TYPE_INPUT){//手工录入属性没有属性值
				$prop_values = $this->input->post('prop_values', array());
				$i = 0;
				foreach($prop_values as $pv){
					$i++;
					CatPropValues::model()->insert(array(
						'prop_id'=>$prop_id,
						'title'=>$pv,
						'sort'=>$i,
						'cat_id'=>$this->input->request('cat_id', 'intval'),
					));
				}
			}
			$this->actionlog(Actionlogs::TYPE_GOODS_PROP, '添加一个商品属性', $prop_id);
			
			Response::output('success', '商品属性添加成功');
		}else{
			Response::output('error', '无数据被提交');
		}
	}
	
	public function delete(){
		$prop_id = $this->input->get('id', 'intval');
		//仅将属性的删除字段置为1，而不改动属性值表，否则无法还原
		CatProps::model()->update(array(
			'deleted'=>1,
		), $prop_id);

		$this->actionlog(Actionlogs::TYPE_GOODS_PROP, '软删除一个商品属性', $prop_id);

		Response::output('success', '一个商品属性被移入回收站 - '.Html::link('撤销', array('admin/prop/undelete', array(
			'id'=>$prop_id,
		))));
	}
	
	public function undelete(){
		$prop_id = $this->input->get('id', 'intval');
		CatProps::model()->update(array(
			'deleted'=>0,
		), $prop_id);
		
		$this->actionlog(Actionlogs::TYPE_GOODS_PROP, '还原一个商品属性', $prop_id);
		
		Response::output('success', '一个商品属性被还原');
	}
	
	public function remove(){
		$prop_id = $this->input->get('id', 'intval');
		CatProps::model()->delete($prop_id);
		CatPropValues::model()->delete(array(
			'prop_id = ?'=>$prop_id,
		));

		$this->actionlog(Actionlogs::TYPE_GOODS_PROP, '永久删除一个商品属性', $prop_id);
		
		Response::output('success', '永久删除一个商品属性');
	}
	
	public function edit(){
		$prop_id = $this->input->get('id', 'intval');
		if($this->input->post()){
			$is_sale_prop = $this->input->post('is_sale_prop', 'intval', 0);
			if($is_sale_prop){
				$required = 1;
			}else{
				$required = $this->input->post('required', 'intval', 0);
			}
			CatProps::model()->update(array(
				'type'=>$this->input->post('type', 'intval'),
				'required'=>$required,
				'title'=>$this->input->post('title'),
				'is_sale_prop'=>$is_sale_prop,
				'is_input_prop'=>$this->input->post('is_input_prop', 'intval', 0),
				'sort'=>$this->input->post('sort', 'intval', 0),
			), $prop_id);

			//删除原有属性值
			$old_prop_value_ids = $this->input->post('old_prop_value_ids', 'intval', array('-1'));
			CatPropValues::model()->update(array(
				'deleted'=>1,
			),array(
				'prop_id = ?'=>$prop_id,
				'id NOT IN ('.implode(',', $old_prop_value_ids).')',
			));
			//设置属性值
			if($this->input->post('type', 'intval') != CatProps::TYPE_INPUT){//手工录入属性没有属性值
				$prop_values = $this->input->post('prop_values', array());
				$i = 0;
				foreach($prop_values as $key => $pv){
					$i++;
					if(in_array($key, $old_prop_value_ids)){
						CatPropValues::model()->update(array(
							'title'=>$pv,
							'sort'=>$i,
						), array(
							'id = ?'=>$key,
						));
					}else{
						CatPropValues::model()->insert(array(
							'prop_id'=>$prop_id,
							'title'=>$pv,
							'sort'=>$i,
							'cat_id'=>$this->input->request('cat_id', 'intval'),
						));
					}
				}
			}
			$this->actionlog(Actionlogs::TYPE_GOODS_PROP, '编辑一个商品属性', $prop_id);
		}
		$prop = CatProps::model()->find($prop_id);
		$this->view->prop = $prop;
		$cat = Categories::model()->find($prop['cat_id'], 'id,title');
		$this->layout->sublink = array(
			'uri'=>array('admin/prop/index', array(
				'cid'=>$cat['id'],
			)),
			'text'=>'返回属性列表',
		);
		$this->layout->subtitle = Html::encode($cat['title']) . ' - 分类属性 - ' . $prop['title'] . '（编辑）';
		$this->view->prop_values = CatPropValues::model()->fetchAll(array(
			'prop_id = ?'=>$prop['id'],
			'deleted = 0',
		), '*', 'sort');
		
		$this->form()->setData($prop);
		
		$sql = new Sql();
		$sql->from('cat_props')
			->where(array(
				'deleted = 0',
				'cat_id = ?'=>$prop['cat_id'],
			))
			->order('sort');
		$listview = new ListView($sql);
		$listview->pageSize = 15;
		$this->view->listview = $listview;
		
		$this->view->render();
	}
	
	public function sort(){
		$prop_id = $this->input->get('id', 'intval');
		$result = CatProps::model()->update(array(
			'sort'=>$this->input->get('sort', 'intval'),
		), array(
			'id = ?'=>$prop_id,
		));
		$this->actionlog(Actionlogs::TYPE_GOODS_PROP, '改变了商品属性排序', $prop_id);
		
		$prop = CatProps::model()->find($prop_id, 'sort');
		Response::output('success', array(
			'message'=>'一个商品属性的排序值被编辑',
			'sort'=>$prop['sort'],
		));
	}
	
	public function isAliasNotExist(){
		$alias = $this->input->post('value', 'trim');
		if(Props::model()->fetchRow(array(
			'alias = ?'=>$alias,
			'id != ?'=>$this->input->get('id', 'intval', 0),
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
}