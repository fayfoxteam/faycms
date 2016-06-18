<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Categories;
use fay\helpers\Html;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\GoodsCatProps;
use fay\models\tables\GoodsCatPropValues;
use fay\models\tables\Actionlogs;
use fay\core\Response;
use fay\models\Category;
use fay\core\HttpException;

/**
 * 商品属性
 */
class GoodsCatPropController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'goods';
	}
	
	public function index(){
		$this->layout->sublink = array(
			'uri'=>array('admin/goods/cat'),
			'text'=>'返回商品分类',
		);
		
		$cat_id = $this->input->get('cat_id', 'intval');
		$cat = Category::model()->get($cat_id, 'id,title');
		if(!$cat){
			throw new HttpException('指定商品分类不存在');
		}
		
		$this->form()->setModel(GoodsCatProps::model());
		
		$this->layout->subtitle = Html::encode($cat['title']) . ' - 分类属性';
		
		$this->_setListview($cat_id);
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
			$prop_id = GoodsCatProps::model()->insert(array(
				'alias'=>$this->input->post('alias', 'trim'),
				'cat_id'=>$this->input->post('cat_id', 'intval'),
				'type'=>$this->input->post('type', 'intval'),
				'required'=>$required,
				'title'=>$this->input->post('title'),
				'is_sale_prop'=>$is_sale_prop,
				'is_input_prop'=>$this->input->post('is_input_prop', 'intval', 0),
				'sort'=>$this->input->post('sort', 'intval', 0),
			));
			
			//设置属性值
			if($this->input->post('type', 'intval') != GoodsCatProps::TYPE_INPUT){//手工录入属性没有属性值
				$prop_values = $this->input->post('prop_values', array());
				$i = 0;
				foreach($prop_values as $pv){
					$i++;
					GoodsCatPropValues::model()->insert(array(
						'prop_id'=>$prop_id,
						'title'=>$pv,
						'sort'=>$i,
						'cat_id'=>$this->input->request('cat_id', 'intval'),
					));
				}
			}
			$this->actionlog(Actionlogs::TYPE_GOODS_PROP, '添加一个商品属性', $prop_id);
			
			Response::notify('success', '商品属性添加成功');
		}else{
			Response::notify('error', '无数据被提交');
		}
	}
	
	public function delete(){
		$prop_id = $this->input->get('id', 'intval');
		//仅将属性的删除字段置为1，而不改动属性值表，否则无法还原
		GoodsCatProps::model()->update(array(
			'deleted'=>1,
		), $prop_id);

		$this->actionlog(Actionlogs::TYPE_GOODS_PROP, '软删除一个商品属性', $prop_id);

		Response::notify('success', '一个商品属性被移入回收站 - '.Html::link('撤销', array('admin/goods-cat-prop/undelete', array(
			'id'=>$prop_id,
		))));
	}
	
	public function undelete(){
		$prop_id = $this->input->get('id', 'intval');
		GoodsCatProps::model()->update(array(
			'deleted'=>0,
		), $prop_id);
		
		$this->actionlog(Actionlogs::TYPE_GOODS_PROP, '还原一个商品属性', $prop_id);
		
		Response::notify('success', '一个商品属性被还原');
	}
	
	public function remove(){
		$prop_id = $this->input->get('id', 'intval');
		GoodsCatProps::model()->delete($prop_id);
		GoodsCatPropValues::model()->delete(array(
			'prop_id = ?'=>$prop_id,
		));

		$this->actionlog(Actionlogs::TYPE_GOODS_PROP, '永久删除一个商品属性', $prop_id);
		
		Response::notify('success', '永久删除一个商品属性');
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
			GoodsCatProps::model()->update(array(
				'alias'=>$this->input->post('alias', 'trim'),
				'type'=>$this->input->post('type', 'intval'),
				'required'=>$required,
				'title'=>$this->input->post('title'),
				'is_sale_prop'=>$is_sale_prop,
				'is_input_prop'=>$this->input->post('is_input_prop', 'intval', 0),
				'sort'=>$this->input->post('sort', 'intval', 0),
			), $prop_id);

			//删除原有属性值
			$old_prop_value_ids = $this->input->post('old_prop_value_ids', 'intval', array('-1'));
			GoodsCatPropValues::model()->update(array(
				'deleted'=>1,
			),array(
				'prop_id = ?'=>$prop_id,
				'id NOT IN ('.implode(',', $old_prop_value_ids).')',
			));
			//设置属性值
			if($this->input->post('type', 'intval') != GoodsCatProps::TYPE_INPUT){//手工录入属性没有属性值
				$prop_values = $this->input->post('prop_values', 'trim', array());
				$i = 0;
				foreach($prop_values as $key => $pv){
					$i++;
					if(in_array($key, $old_prop_value_ids)){
						GoodsCatPropValues::model()->update(array(
							'title'=>$pv,
							'sort'=>$i,
						), array(
							'id = ?'=>$key,
						));
					}else{
						GoodsCatPropValues::model()->insert(array(
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
		$prop = GoodsCatProps::model()->find($prop_id);
		$this->view->prop = $prop;
		$cat = Categories::model()->find($prop['cat_id'], 'id,title');
		$this->layout->sublink = array(
			'uri'=>array('admin/goods-cat-prop/index', array(
				'cat_id'=>$cat['id'],
			)),
			'text'=>'返回属性列表',
		);
		$this->layout->subtitle = Html::encode($cat['title']) . ' - 分类属性 - ' . $prop['title'];
		$this->view->prop_values = GoodsCatPropValues::model()->fetchAll(array(
			'prop_id = ?'=>$prop['id'],
			'deleted = 0',
		), '*', 'sort');
		
		$this->form()->setData($prop);
		
		$this->_setListview($prop['cat_id']);
		
		$this->view->render();
	}
	
	public function sort(){
		$id = $this->input->get('id', 'intval');
		$result = GoodsCatProps::model()->update(array(
			'sort'=>$this->input->get('sort', 'intval'),
		), array(
			'id = ?'=>$id,
		));
		$this->actionlog(Actionlogs::TYPE_GOODS_PROP, '改变了商品属性排序', $id);
		
		$data = GoodsCatProps::model()->find($id, 'sort');
		Response::notify('success', array(
			'message'=>'一个商品属性的排序值被编辑',
			'data'=>array(
				'sort'=>$data['sort'],
			),
		));
	}
	
	public function isAliasNotExist(){
		if(GoodsCatProps::model()->fetchRow(array(
			'alias = ?'=>$this->input->request('alias', 'trim'),
			'id != ?'=>$this->input->request('id', 'intval', false),
		))){
			echo Response::json('', 0, '别名已存在');
		}else{
			echo Response::json('', 1, '别名不存在');
		}
	}
	/**
	 * 设置右侧项目列表
	 */
	private function _setListview($cat_id){
		$sql = new Sql();
		$sql->from('goods_cat_props')
			->where(array(
				'deleted = 0',
				'cat_id IN ('.implode(',', Category::model()->getParentIds($cat_id)).')',
			))
			->order('sort, id DESC');
		$listview = new ListView($sql, array(
			'page_size'=>15,
			'empty_text'=>'<tr><td colspan="4" align="center">无相关记录！</td></tr>',
		));
		$this->view->listview = $listview;
		
	}
}