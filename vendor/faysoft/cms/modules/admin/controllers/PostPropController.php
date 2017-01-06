<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Categories;
use fay\helpers\Html;
use fay\models\tables\Props;
use fay\services\post\Prop;
use fay\models\tables\Actionlogs;
use fay\services\CategoryService;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Response;
use fay\core\HttpException;

class PostPropController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'post';
	}
	
	public function index(){
		$this->layout->sublink = array(
			'uri'=>array('admin/post/cat'),
			'text'=>'返回文章分类',
		);
		
		$cat_id = $this->input->get('cat_id', 'intval');
		
		$cat = Categories::model()->fetchRow(array(
			'id = ?'=>$cat_id,
		), 'title');
		if(!$cat){
			throw new HttpException('指定分类不存在');
		}
		
		$this->form()->setModel(Props::model())
			->setData(array(
				'refer'=>$cat_id,
			));
		
		$this->layout->subtitle = '文章分类属性 - 分类: '.Html::encode($cat['title']);
		
		$this->_setListview($cat_id);
		$this->view->render();
	}
	
	public function create(){
		if(!$this->input->post()){
			throw new HttpException('无数据提交', 500);
		}
		
		if($this->form()->setModel(Props::model())->check()){
			$refer = $this->input->post('refer', 'intval');
			$prop = $this->form()->getFilteredData();
			$values = $this->input->post('prop_values', array());
			$prop_id = PropService::service()->create($refer, $prop, $values);
			
			$this->actionlog(Actionlogs::TYPE_POST_CAT, '添加了一个文章分类属性', $prop_id);
			
			Response::notify('success', array(
				'message'=>'文章分类属性添加成功',
				'id'=>$prop_id,
			));
		}else{
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
			
			$prop_values = $this->input->post('prop_values', array());
			$ids = $this->input->post('ids', 'intval', array('-1'));
			
			PropService::service()->update($refer, $prop_id, $prop, $prop_values, $ids);
			
			$this->actionlog(Actionlogs::TYPE_POST_CAT, '编辑了文章分类属性信息', $prop_id);
			
			Response::notify('success', '文章分类属性编辑成功', false);
		}
		
		$prop = PropService::service()->get($prop_id);

		if(!$prop){
			throw new HttpException('所选文章分类属性不存在');
		}
		$this->form()->setData($prop);
		$this->view->prop = $prop;
		
		$this->layout->sublink = array(
			'uri'=>array('admin/post-prop/index', array('cat_id'=>$prop['refer'])),
			'text'=>'添加文章分类属性',
		);
		$cat = Categories::model()->find($prop['refer'], 'title');
		$this->layout->subtitle = '编辑文章分类属性 - '.Html::encode($cat['title']).' - '.Html::encode($prop['title']);
		
		$this->_setListview($prop['refer']);
		$this->view->refer = $prop['refer'];
		
		$this->view->render();
	}
	
	public function delete(){
		$id = $this->input->get('id', 'intval');
		$prop = Props::model()->find($id, 'refer');
		PropService::service()->delete($id);
		$this->actionlog(Actionlogs::TYPE_POST_CAT, '删除了一个文章分类属性', $id);
		
		//不能直接回到上一页，因为可能处在编辑状态
		Response::notify('success', '一个文章分类属性被删除', array('admin/post-prop/index', array(
			'cat_id'=>$prop['refer'],
		)));
	}

	public function sort(){
		$id = $this->input->get('id', 'intval');
		Props::model()->update(array(
			'sort'=>$this->input->get('sort', 'intval'),
		), array(
			'id = ?'=>$id,
		));
		$this->actionlog(Actionlogs::TYPE_POST_CAT, '改变了文章分类属性排序', $id);
		
		$data = Props::model()->find($id, 'sort');
		Response::notify('success', array(
			'message'=>'一个文章分类属性排序值被编辑',
			'data'=>array(
				'sort'=>$data['sort'],
			),
		));
	}
	
	/**
	 * 设置右侧项目列表
	 * @param int $cat_id
	 */
	private function _setListview($cat_id){
		$cat = CategoryService::service()->get($cat_id, 'left_value,right_value');
		$cat_parents = Categories::model()->fetchCol('id', array(
			'left_value <= '.$cat['left_value'],
			'right_value >= '.$cat['right_value'],
		));
		$sql = new Sql();
		$sql->from('props')
			->where(array(
				'deleted = 0',
				'type = '.Props::TYPE_POST_CAT,
				'refer IN ('.implode(',', $cat_parents).')',
			))
			->order('sort, id DESC');
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>15,
			'empty_text'=>'<tr><td colspan="4" align="center">无相关记录！</td></tr>',
		));
	}
}