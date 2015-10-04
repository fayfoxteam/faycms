<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Categories;
use fay\models\Category;
use fay\models\tables\Actionlogs;
use fay\helpers\Pinyin;
use fay\core\Response;
use fay\helpers\Html;
use fay\models\Flash;

class CategoryController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'cat';
	}
	
	public function index(){
		Flash::set('这是一个汇总表，如果您不清楚它的含义，请不要随意修改，后果可能很严重！', 'attention');
		$this->layout->subtitle = '分类管理';
		$this->view->cats = Category::model()->getTree();
		$this->view->root = 0;
		$this->layout->sublink = array(
			'uri'=>'#create-cat-dialog',
			'text'=>'添加根分类',
			'html_options'=>array(
				'class'=>'create-cat-link',
				'data-title'=>'系统根分类',
				'data-id'=>0,
			),
		);
		$this->view->render();
	}
	
	public function create(){
		$this->form()->setModel(Categories::model());
		if($this->input->post()){
			if($this->form()->check()){
				$data = $this->form()->getFilteredData();
				empty($data['is_nav']) && $data['is_nav'] = 0;
				empty($data['file_id']) && $data['file_id'] = 0;
				empty($data['alias']) && $data['alias'] = $this->getCatAlias($data['title']);
				
				$parent = $this->input->post('parent', 'intval', 0);
				$sort = $this->input->post('sort', 'intval', 1000);
				
				$cat_id = Category::model()->create($parent, $sort, $data);
				
				$this->actionlog(Actionlogs::TYPE_CATEGORY, '添加分类', $cat_id);
				
				$cat = Categories::model()->find($cat_id);
				Response::output('success', array(
					'cat'=>$cat,
 					'message'=>'分类<span class="fc-orange">'.Html::encode($cat['title']).'</span>添加成功',
				));
			}else{
				Response::output('error', '参数异常');
			}
		}else{
			Response::output('error', '请提交数据');
		}
	}
	
	/**
	 * 获取唯一的别名（遇到中文会将其转为拼音）
	 */
	private function getCatAlias($title = '', $spelling = null, $dep = 0){
		if(!$spelling){
			if($title){
				if(preg_match('/[^\x00-\x80]/', $title)){//如果包含中文，将中文转成拼音
					$spelling = Pinyin::change($title);
				}else{
					$spelling = $title;
				}
			}else{
				return $title;
			}
		}
		$alias = $dep ? $spelling.'-'.$dep : $spelling;
		$cat = Categories::model()->fetchRow(array('alias = ?'=>$alias), 'id');
		if($cat){
			return $this->getCatAlias('', $spelling, $dep+1);
		}else{
			return $alias;
		}
	}
	
	public function edit(){
		if($this->input->post()){
			if($this->form()->setModel(Categories::model())->check()){
				$cat_id = $this->input->post('id', 'intval');
				$data = $this->form()->getFilteredData();
				empty($data['is_nav']) && $data['is_nav'] = 0;
				empty($data['file_id']) && $data['file_id'] = 0;
				
				$parent = $this->input->post('parent', 'intval', null);
				$sort = $this->input->post('sort', 'intval', null);
				
				Category::model()->update($cat_id, $data, $sort, $parent);
				
				$this->actionlog(Actionlogs::TYPE_CATEGORY, '修改分类', $cat_id);
				
				$cat = Categories::model()->find($cat_id);
				Response::output('success', array(
					'message'=>'分类<span class="fc-orange">'.Html::encode($cat['title']).'</span>编辑成功',
					'cat'=>$cat,
				));
			}else{
				Response::output('error', '参数异常');
			}
		}else{
			Response::output('error', '请提交数据');
		}
	}
	
	public function remove(){
		if(Category::model()->remove($this->input->get('id', 'intval'))){
			$this->actionlog(Actionlogs::TYPE_CATEGORY, '移除分类', $this->input->get('id', 'intval'));
			
			Response::output('success', array(
				'message'=>'一个分类被移除',
			));
		}else{
			Response::output('error', '请提交数据');
		}
	}
	
	public function removeAll(){
		if(Category::model()->removeAll($this->input->get('id', 'intval'))){
			$this->actionlog(Actionlogs::TYPE_CATEGORY, '移除分类及其所有子分类', $this->input->get('id', 'intval'));
				
			Response::output('success', array(
				'message'=>'一个分类分支被移除',
			));
		}else{
			Response::output('error', '请提交数据');
		}
	}
	
	/**
	 * 获取指定id对应的分类，及该分类下的所有子分类
	 */
	public function get(){
		$cat = Categories::model()->find($this->input->get('id', 'intval'));
		$children = Categories::model()->fetchCol('id', array(
			'left_value > '.$cat['left_value'],
			'right_value < '.$cat['right_value'],
		));
		
		Response::json(array(
			'cat'=>$cat,
			'children'=>$children,
		));
	}
	
	public function sort(){
		$id = $this->input->get('id', 'intval');
		Category::model()->sort($id, $this->input->get('sort', 'intval'));
		$this->actionlog(Actionlogs::TYPE_CATEGORY, '改变了分类排序', $id);
		
		$node = Categories::model()->find($id, 'sort,title');
		Response::output('success', array(
			'data'=>array(
				'sort'=>$node['sort'],
			),
			'message'=>"分类{$node['title']}的排序值被修改为{$node['sort']}",
		));
	}
	
	public function reindex(){
		Category::model()->buildIndex();
	}
	
	public function setIsNav(){
		Categories::model()->update(array(
			'is_nav'=>$this->input->get('is_nav', 'intval', 0),
		), $this->input->get('id', 'intval'));
		
		$cat = Categories::model()->find($this->input->get('id', 'intval'), 'is_nav');
		Response::output('success', array(
			'data'=>array(
				'is_nav'=>$cat['is_nav'],
			),
		));
	}
	
	public function isAliasNotExist(){
		$alias = $this->input->post('value', 'trim');
		if(Categories::model()->fetchRow(array(
			'alias = ?'=>$alias,
			'id != ?'=>$this->input->get('id', 'intval', false),
		))){
			echo Response::json('', 0, '别名已存在');
		}else{
			echo Response::json('', 1, '别名不存在');
		}
	}
}