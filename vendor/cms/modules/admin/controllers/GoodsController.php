<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Goods;
use fay\models\tables\GoodsFiles;
use fay\models\tables\CatPropValues;
use fay\models\tables\GoodsPropValues;
use fay\models\tables\GoodsSkus;
use fay\models\tables\Actionlogs;
use fay\models\tables\Categories;
use fay\models\tables\CatProps;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\Category;
use fay\helpers\Date;
use fay\models\GoodsModel;
use fay\core\Response;
use fay\helpers\Html;

class GoodsController extends AdminController{
	public $boxes = array('sku', 'guide', 'shipping', 'publish-time', 'thumbnail', 'seo',
		'gallery');
	
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'goods';
		if(!$this->input->isAjaxRequest()){
			$this->flash->set('这个模块只是做着玩的，并没有实现购物功能。', 'attention');
		}
	}
	
	public function create(){
		$this->layout->subtitle = '添加商品';
		
		if($this->input->post()){
			//插入goods表
			$goods_data = Goods::model()->setAttributes($this->input->post());
			$goods_data['create_time'] = $this->current_time;
			$goods_data['cat_id'] = $this->input->get('cid');
			!empty($goods_data['publish_time']) || $goods_data['publish_time'] = $this->current_time;
			!empty($goods_data['sub_stock']) || $goods_data['sub_stock'] = Goods::SUB_STOCK_PAY;
			
			$goods_id = Goods::model()->insert($goods_data);
			
			//设置gallery
			$desc = $this->input->post('desc');
			$photos = $this->input->post('photos', 'intval', array());
			$i = 0;
			foreach($photos as $p){
				$i++;
				GoodsFiles::model()->insert(array(
					'file_id'=>$p,
					'goods_id'=>$goods_id,
					'desc'=>$desc[$p],
					'position'=>$i,
					'create_time'=>$this->current_time,
				));
			}
			
			//属性别名
			$cp_alias = $this->input->post('cp_alias');
			//普通属性
			foreach($this->input->post('cp', null, array()) as $k=>$v){
				$k = intval($k);
				if(is_array($v)){//多选属性
					foreach($v as $v2){
						$v2 = intval($v2);
						if(!empty($cp_alias[$k][$v2])){
							//若有属性值传过来，则以输入值作为属性值
							$prop_value_alias = $cp_alias[$k][$v2];
						}else{
							//若没有属性值传过来，则以默认值作为属性值
							$cat_prop_value = CatPropValues::model()->fetchRow(array(
								'id = ?'=>$v2,
							));
							$prop_value_alias = $cat_prop_value['title'];
						}
						GoodsPropValues::model()->insert(array(
							'goods_id'=>$goods_id,
							'prop_id'=>$k,
							'prop_value_id'=>$v2,
							'prop_value_alias'=>$prop_value_alias,
						));
					}
				}else{//单选属性或输入属性
					$v = intval($v);
					if($v != 0){//属性值id为0，则意味着这个属性是{手工录入属性}
						if(!empty($cp_alias[$k][$v])){
							//若有属性值传过来，则以输入值作为属性值
							$prop_value_alias = $cp_alias[$k][$v];
						}else{
							//若没有属性值传过来，则以默认值作为属性值
							$cat_prop_value = CatPropValues::model()->fetchRow(array(
								'id = ?'=>$v,
							));
							$prop_value_alias = $cat_prop_value['title'];
						}
						GoodsPropValues::model()->insert(array(
							'goods_id'=>$goods_id,
							'prop_id'=>$k,
							'prop_value_id'=>$v,
							'prop_value_alias'=>$prop_value_alias,
						));
					}else{
						if(!empty($cp_alias[$k][$v])){
							//若有属性值传过来，则设置属性值
							//若没有，则跳过此属性
							$prop_value_alias = $cp_alias[$k][$v];
							GoodsPropValues::model()->insert(array(
								'goods_id'=>$goods_id,
								'prop_id'=>$k,
								'prop_value_id'=>$v,
								'prop_value_alias'=>$prop_value_alias,
							));
						}
					}
				}
			}
			
			//销售属性
			foreach($this->input->post('cp_sale', null, array()) as $k=>$v){
				//销售属性必是多选属性，且必然设置了alias
				foreach($v as $v2){
					$v2 = intval($v2);
					GoodsPropValues::model()->insert(array(
						'goods_id'=>$goods_id,
						'prop_id'=>$k,
						'prop_value_id'=>$v2,
						'prop_value_alias'=>$cp_alias[$k][$v2],
					));
				}
			}
			
			//sku
			$prices = $this->input->post('prices', 'floatval', array());
			$quantities = $this->input->post('quantities', 'intval', array());
			$tsces = $this->input->post('tsces', array());
			foreach($prices as $k => $p){
				GoodsSkus::model()->insert(array(
					'goods_id'=>$goods_id,
					'prop_value_ids'=>$k,
					'price'=>$p,
					'quantity'=>$quantities[$k],
					'tsces'=>$tsces[$k],
				));
			}

			$this->actionlog(Actionlogs::TYPE_GOODS, '添加一个商品', $goods_id);
		}
		$this->form()->setData($this->input->post());
		
		//获取分类
		$cat = Categories::model()->find($this->input->get('cid', 'intval'), 'id,title');
		$this->view->cat = $cat;
		
		//props
		$props = CatProps::model()->fetchAll(array(
			"cat_id = {$cat['id']}",
			'deleted = 0',
		), '!deleted', 'sort, id');
		
		//prop_values
		$prop_values = CatPropValues::model()->fetchAll(array(
			"cat_id = {$cat['id']}",
			'deleted = 0',
		), '!deleted', 'prop_id, sort');
		
		//合并属性和属性值
		foreach($props as &$p){
			$p['prop_values'] = array();
			foreach($prop_values as $pv){
				if($pv['prop_id'] != $p['id'])continue;
				$p['prop_values'][] = $pv;
			}
		}
		
		$this->view->props = $props;
		
		$this->view->boxes = $this->boxes;
		$this->view->render();
	}
	
	public function index(){
		$this->layout->subtitle = '商品';
		
		$this->layout->sublink = array(
			'uri'=>array('admin/category/goods'),
			'text'=>'添加商品',
		);

		$this->form()->setData($this->input->get());
		
		$sql = new Sql();
		$sql->from('goods', 'g')
			->joinLeft('categories', 'c', 'g.cat_id = c.id', 'title AS cat_title');
		$conditions = array(
			'g.deleted = 0',
		);
		if($this->input->get('keywords')){
			$conditions["g.{$this->input->get("field")} like ?"] = '%'.$this->input->get('keywords').'%';
		}
		if($this->input->get('start_time')){
			$conditions["g.{$this->input->get("time_field")} > ?"] = $this->input->get('start_time','strtotime');
		}
		if($this->input->get('end_time')){
			$conditions["g.{$this->input->get("time_field")} < ?"] = $this->input->get('end_time', 'strtotime');
		}
		if($this->input->get('cat_id')){
			$conditions['g.cat_id = ?'] = $this->input->get('cat_id', 'intval');
		}
		if($this->input->get('status')){
			$conditions['g.status = ?'] = $this->input->get('status', 'intval');
		}
		$sql->where($conditions);
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>20,
		));

		$this->view->cats = Category::model()->getTree('_system_goods');
		$this->view->render();
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑商品';
		
		$goods_id = $this->input->get('id', 'intval');
		
		if($this->input->post()){
			//更新goods表
			$goods_data = Goods::model()->setAttributes($this->input->post());
			$goods_data['last_modified_time'] = $this->current_time;
			Goods::model()->update($goods_data, $goods_id);
			
			//设置gallery
			$desc = $this->input->post('desc');
			$photos = $this->input->post('photos', 'intval', array());
			//删除已被删除的图片
			if($photos){
				GoodsFiles::model()->delete(array(
					'goods_id = ?'=>$goods_id,
					'file_id NOT IN ('.implode(',', $photos).')',
				));
			}else{
				GoodsFiles::model()->delete(array(
					'goods_id = ?'=>$goods_id,
				));
			}
			//获取已存在的图片
			$old_files_ids = GoodsFiles::model()->fetchCol('file_id', array(
				'goods_id = ?'=>$goods_id,
			));
			$i = 0;
			foreach($photos as $p){
				$i++;
				if(in_array($p, $old_files_ids)){
					GoodsFiles::model()->update(array(
						'desc'=>$desc[$p],
						'position'=>$i,
					), array(
						'goods_id = ?'=>$goods_id,
						'file_id = ?'=>$p,
					));
				}else{
					GoodsFiles::model()->insert(array(
						'file_id'=>$p,
						'goods_id'=>$goods_id,
						'desc'=>$desc[$p],
						'position'=>$i,
						'create_time'=>$this->current_time,
					));
				}
			}
			
			//属性别名
			$cp_alias = $this->input->post('cp_alias');
			

			$new_prop_values = array();//记录所有属性（普通属性+销售属性）
			$old_prop_values = GoodsPropValues::model()->fetchCol('prop_value_id', array(
				'goods_id = ?'=>$goods_id,
			));//所有原属性（普通属性+销售属性）
			//普通属性
			foreach($this->input->post('cp', null, array()) as $k=>$v){
				$k = intval($k);
				if(is_array($v)){//多选属性
					foreach($v as $v2){
						$v2 = intval($v2);
						$new_prop_values[] = $v2;
						if(!empty($cp_alias[$k][$v2])){
							//若有属性值传过来，则以输入值作为属性值
							$prop_value_alias = $cp_alias[$k][$v2];
						}else{
							//若没有属性值传过来，则以默认值作为属性值
							$cat_prop_value = CatPropValues::model()->fetchRow(array(
								'id = ?'=>$v2,
							));
							$prop_value_alias = $cat_prop_value['title'];
						}
						if(in_array($v2, $old_prop_values)){
							GoodsPropValues::model()->update(array(
								'prop_value_alias'=>$prop_value_alias,
							), array(
								'goods_id = ?'=>$goods_id,
								'prop_value_id = ?'=>$v2,
							));
						}else{
							GoodsPropValues::model()->insert(array(
								'goods_id'=>$goods_id,
								'prop_id'=>$k,
								'prop_value_id'=>$v2,
								'prop_value_alias'=>$prop_value_alias,
							));
						}
					}
				}else{//单选属性或输入属性
					$v = intval($v);
					$new_prop_values[] = $v;
					if($v != 0){//属性值id为0，则意味着这个属性是{手工录入属性}
						if(!empty($cp_alias[$k][$v])){
							//若有属性值传过来，则以输入值作为属性值
							$prop_value_alias = $cp_alias[$k][$v];
						}else{
							//若没有属性值传过来，则以默认值作为属性值
							$cat_prop_value = CatPropValues::model()->fetchRow(array(
								'id = ?'=>$v,
							));
							$prop_value_alias = $cat_prop_value['title'];
						}
						if(in_array($v, $old_prop_values)){
							//只改了别名
							GoodsPropValues::model()->update(array(
								'prop_value_alias'=>$prop_value_alias,
							), array(
								'goods_id = ?'=>$goods_id,
								'prop_value_id = ?'=>$v,
							));
						}else{
							if(GoodsPropValues::model()->fetchRow(array(
								'goods_id = ?'=>$goods_id,
								'prop_id = ?'=>$k,
							))){//单值属性若已存在，直接更新，不重新插入
								GoodsPropValues::model()->update(array(
									'prop_value_alias'=>$prop_value_alias,
									'prop_value_id'=>$v,
								), array(
									'goods_id = ?'=>$goods_id,
									'prop_id = ?'=>$k,
								));
							}else{
								GoodsPropValues::model()->insert(array(
									'goods_id'=>$goods_id,
									'prop_id'=>$k,
									'prop_value_id'=>$v,
									'prop_value_alias'=>$prop_value_alias,
								));
							}
						}
					}else{
						if(!empty($cp_alias[$k][$v])){
							//若有属性值传过来，则设置属性值
							//若没有，则跳过此属性
							$prop_value_alias = $cp_alias[$k][$v];
							if(in_array($v, $old_prop_values)){
								GoodsPropValues::model()->update(array(
									'prop_value_alias'=>$prop_value_alias,
								), array(
									'goods_id = ?'=>$goods_id,
									'prop_value_id = ?'=>$v,
								));
							}else{
								if(GoodsPropValues::model()->fetchRow(array(
									'goods_id = ?'=>$goods_id,
									'prop_id = ?'=>$k,
								))){//单值属性若已存在，直接更新，不重新插入
									GoodsPropValues::model()->update(array(
										'prop_value_alias'=>$prop_value_alias,
										'prop_value_id'=>$v,
									), array(
										'goods_id = ?'=>$goods_id,
										'prop_id = ?'=>$k,
									));
								}else{
									GoodsPropValues::model()->insert(array(
										'goods_id'=>$goods_id,
										'prop_id'=>$k,
										'prop_value_id'=>$v,
										'prop_value_alias'=>$prop_value_alias,
									));
								}
							}
						}
					}
				}
			}
			
			//销售属性
			foreach($this->input->post('cp_sale', null, array()) as $k=>$v){
				//销售属性必是多选属性，且必然设置了alias
				foreach($v as $v2){
					$v2 = intval($v2);
					$new_prop_values[] = $v2;
					if(in_array($v2, $old_prop_values)){
						GoodsPropValues::model()->update(array(
							'prop_value_alias'=>$cp_alias[$k][$v2],
						), array(
							'goods_id = ?'=>$goods_id,
							'prop_value_id = ?'=>$v2,
						));
					}else{
						GoodsPropValues::model()->insert(array(
							'goods_id'=>$goods_id,
							'prop_id'=>$k,
							'prop_value_id'=>$v2,
							'prop_value_alias'=>$cp_alias[$k][$v2],
						));
					}
				}
			}
			//删除已被删除的所有属性（普通属性+销售属性）
			GoodsPropValues::model()->delete(array(
				'goods_id = ?'=>$goods_id,
				'prop_value_id NOT IN ('.implode(',', $new_prop_values).')',
			));
				
			//sku
			$prices = $this->input->post('prices', 'floatval', array());
			$quantities = $this->input->post('quantities', 'intval', array());
			$tsces = $this->input->post('tsces', array());
			$old_skus = GoodsSkus::model()->fetchCol('prop_value_ids', array(
				'goods_id = ?'=>$goods_id,
			));
			//删除已被删除的sku
			$new_sku_keys = array_keys($prices);
			GoodsSkus::model()->delete(array(
				'goods_id = ?'=>$goods_id,
				"prop_value_ids NOT IN ('".implode("','", $new_sku_keys)."')"
			));
			foreach($prices as $k => $p){
				if(in_array($k, $old_skus)){
					GoodsSkus::model()->update(array(
						'goods_id'=>$goods_id,
						'price'=>$p,
						'quantity'=>$quantities[$k],
						'tsces'=>$tsces[$k],
					), array(
						'prop_value_ids = ?'=>$k,
					));
				}else{
					GoodsSkus::model()->insert(array(
						'goods_id'=>$goods_id,
						'prop_value_ids'=>$k,
						'price'=>$p,
						'quantity'=>$quantities[$k],
						'tsces'=>$tsces[$k],
					));
				}
			}
			
			$this->flash->set('一个商品被编辑', 'success');
			$this->actionlog(Actionlogs::TYPE_GOODS, '编辑一个商品', $goods_id);
		}
		
		$goods = GoodsModel::model()->get($goods_id);
		//做一些格式化处理
		$goods['publish_time'] = Date::format($goods['publish_time']);
		
		//获取分类
		$cat = Categories::model()->find($goods['cat_id'], 'id,title');
		
		//props
		$props = CatProps::model()->fetchAll(array(
			"cat_id = {$cat['id']}",
			'deleted = 0',
		), '!deleted', 'sort, id');
		
		//prop_values
		$prop_values = CatPropValues::model()->fetchAll(array(
			"cat_id = {$cat['id']}",
			'deleted = 0',
		), '!deleted', 'prop_id, sort');
		
		//合并属性和属性值
		foreach($props as &$p){
			$p['prop_values'] = array();
			foreach($prop_values as $pv){
				if($pv['prop_id'] != $p['id'])continue;
				$p['prop_values'][] = $pv;
			}
		}
		
		$this->view->props = $props;
		
		$this->view->goods = $goods;
		$this->form()->setData($goods);

		$this->view->boxes = $this->boxes;
		$this->view->render();
	}
	
	public function delete(){
		$goods_id = $this->input->get('id', 'intval');
		Goods::model()->update(array(
			'deleted'=>1
		), $goods_id);
		$this->actionlog(Actionlogs::TYPE_GOODS, '软删除一个商品', $goods_id);
		
		Response::output('success', array(
			'message'=>'一个商品被移入回收站 - '.Html::link('撤销', array('admin/goods/undelete', array(
				'id'=>$goods_id,
			))),
			'id'=>$goods_id
		));
	}
	
	public function undelete(){
		$goods_id = $this->input->get('id', 'intval');
		Goods::model()->update(array(
			'deleted'=>0
		), array('id = ?'=>$goods_id));
		$this->actionlog(Actionlogs::TYPE_GOODS, '将商品移出回收站', $goods_id);
		
		Response::output('success', array(
			'message'=>'一个商品被还原',
			'id'=>$goods_id
		));
	}
	
	public function remove(){

	}
	
	public function setIsNew(){
		Goods::model()->update(array(
			'is_new'=>$this->input->get('is_new', 'intval'),
		), $this->input->get('id', 'intval'));
		
		$goods = Goods::model()->find($this->input->get('id', 'intval'), 'is_new');
		Response::output('success', array(
			'message'=>'',
			'is_new'=>$goods['is_new'],
		));
	}
	
	public function setIsHot(){
		Goods::model()->update(array(
			'is_hot'=>$this->input->get('is_hot', 'intval'),
		), $this->input->get('id', 'intval'));
		
		$goods = Goods::model()->find($this->input->get('id', 'intval'), 'is_hot');
		Response::output('success', array(
			'message'=>'',
			'is_hot'=>$goods['is_hot'],
		));
	}
	
	public function setSort(){
		Goods::model()->update(array(
			'sort'=>$this->input->get('sort', 'intval'),
		), $this->input->get('id', 'intval'));
		$goods = Goods::model()->find($this->input->get('id', 'intval'), 'sort');
		
		Response::output('success', array(
			'message'=>'一个商品的排序值被编辑',
			'sort'=>$goods['sort'],
		));
	}
}