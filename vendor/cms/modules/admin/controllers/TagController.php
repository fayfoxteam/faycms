<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Tags;
use fay\models\tables\Actionlogs;
use fay\models\tables\PostsTags;
use fay\core\Sql;
use fay\common\ListView;
use fay\core\Response;
use fay\core\HttpException;
use fay\models\tables\TagCounter;

class TagController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'post';
	}
	
	public function index(){
		$this->layout->subtitle = '标签';
		
		$this->_setListview();
		
		$this->form()->setModel(Tags::model());
		
		$this->view->render();
	}
	
	public function create(){
		$this->form()->setModel(Tags::model());
		if($this->input->post()){
			if($this->form()->check()){
				$data = Tags::model()->fillData($this->input->post());
				$tag_id = Tags::model()->insert($data);
				$this->actionlog(Actionlogs::TYPE_TAG, '创建了标签', $tag_id);

				$tag = Tags::model()->find($tag_id, 'id,title');
				Response::notify('success', array(
					'message'=>'标签创建成功',
					'tag'=>$tag,
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
	
	public function remove(){
		$tag_id = $this->input->get('id', 'intval');
		Tags::model()->delete(array('id = ?'=>$tag_id));
		PostsTags::model()->delete(array('tag_id = ?'=>$tag_id));
		$this->actionlog(Actionlogs::TYPE_TAG, '删除了标签', $tag_id);
		
		Response::notify('success', array(
			'message'=>'一个标签被永久删除',
		));
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑标签';
		$this->layout->sublink = array(
			'uri'=>array('admin/tag/index', $this->input->get()),
			'text'=>'添加标签',
		);
		$tag_id = $this->input->get('id', 'intval');
		$this->form()->setModel(Tags::model());
		if($this->input->post() && $this->form()->check()){
			Tags::model()->update($this->form()->getAllData(), $tag_id, true);
			$this->actionlog(Actionlogs::TYPE_TAG, '编辑了标签', $tag_id);
			Response::notify('success', '一个标签被编辑', false);
		}
		if($tag = Tags::model()->find($tag_id)){
			$this->form()->setData($tag);
			$this->view->tag = $tag;
			
			$this->_setListview();
			
			$this->view->render();
		}else{
			throw new HttpException('无效的ID');
		}
	}
	
	public function isTagNotExist(){
		$id = $this->input->get('id', 'intval');
		$id || $id = false;
		if(Tags::model()->fetchRow(array(
			'title = ?'=>$this->input->post('value', 'trim'),
			'id != ?'=>$id,
		))){
			Response::json('', 0, '标签已存在');
		}else{
			Response::json();
		}
	}
	
	public function sort(){
		$tag_id = $this->input->get('id', 'intval');
		$result = Tags::model()->update(array(
			'sort'=>$this->input->get('sort', 'intval'),
		), array(
			'id = ?'=>$tag_id,
		));
		$this->actionlog(Actionlogs::TYPE_TAG, '改变了标签排序', $tag_id);
		
		$tag = Tags::model()->find($tag_id, 'sort');
		Response::notify('success', array(
			'message'=>'一篇标签的排序值被编辑',
			'sort'=>$tag['sort'],
		));
	}
	
	/**
	 * 设置右侧列表
	 */
	private function _setListview(){
		$sql = new Sql();
		$sql->from(array('t'=>'tags'))
			->joinLeft(array('tc'=>'tag_counter'), 't.id = tc.tag_id', TagCounter::model()->getFields('tag_id'));
		
		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'asc' : 'desc';
			$sql->order("t.{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('t.id DESC');
		}
		
		$this->view->listview = new ListView($sql, array(
			'page_size' => 15,
			'empty_text'=>'<tr><td colspan="3" align="center">无相关记录！</td></tr>',
		));
	}
	
	public function search(){
		$tags = Tags::model()->fetchAll(array(
			'title LIKE ?'=>'%'.$this->input->get('key', false).'%'
		), 'id,title', 'sort, count DESC', 20);
		
		Response::json($tags);
	}
}