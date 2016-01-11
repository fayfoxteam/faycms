<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\PostComments;
use fay\core\Response;
use fay\models\Setting;
use fay\models\tables\Actionlogs;
use fay\models\Option;
use fay\core\Hook;
use fay\models\tables\Posts;
use fay\helpers\ArrayHelper;
use fay\helpers\Html;
use fay\models\post\Comment;
use fay\core\Exception;

class PostCommentController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'message';
	}
	
	public function index(){
		$this->layout->subtitle = '文章评论';
		
		$this->layout->_setting_panel = '_setting_index';
		$_setting_key = 'admin_post_comment_index';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array(
			'cols'=>array('user', 'content', 'post', 'status', 'create_time'),
			'display_name'=>'username',
			'page_size'=>20,
		);
		$this->form('setting')->setModel(Setting::model())
			->setJsModel('setting')
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key,
			));
		
		$sql = new Sql();
		$sql->from('post_comments', 'pc')
			->joinLeft('posts', 'p', 'pc.post_id = p.id', 'title AS post_title')
			->joinLeft('users', 'u', 'pc.user_id = u.id', 'realname,username,nickname')
			->order('id DESC')
		;
		if($this->input->get('deleted')){
			$sql->where(array(
				'pc.deleted = 1',
			));
		}else if($this->input->get('status', 'intval') !== null && $this->input->get('deleted', 'intval') != 1){
			$sql->where(array(
				'pc.status = ?'=>$this->input->get('status', 'intval'),
				'pc.deleted = 0',
			));
		}else{
			$sql->where('pc.deleted = 0');
		}
		
		if($this->input->get('start_time')){
			$sql->where(array('pc.create_time > ?' => $this->input->get('start_time', 'strtotime')));
		}
		if($this->input->get('end_time')){
			$sql->where(array('pc.create_time < ?' => $this->input->get('end_time', 'strtotime')));
		}
		
		//关键词搜索
		if($this->input->get('keywords')){
			if(in_array($this->input->get('keywords_field'), array('pc.content', 'p.title'))){
				$sql->where(array("{$this->input->get('keywords_field')} LIKE ?"=>'%'.$this->input->get('keywords').'%'));
			}else if(in_array($this->input->get('keywords_field'), array('p.id', 'pc.id', 'pc.user_id'))){
				$sql->where(array("{$this->input->get('keywords_field')} = ?"=>$this->input->get('keywords', 'intval')));
			}else{
				$sql->where(array('pc.content LIKE ?'=>'%'.$this->input->get('keywords', 'trim').'%'));
			}
		}
		
		$listview = new ListView($sql, array(
			'page_size'=>!empty($_settings['page_size']) ? $_settings['page_size'] : 20,
			'empty_text'=>'<tr><td colspan="5" align="center">无相关记录！</td></tr>',
		));
		$this->view->listview = $listview;			
		
		$this->view->render();
	}
	
	/**
	 * 返回各状态下的文章评论数
	 */
	public function getCounts(){
		Response::json(array(
			'all'=>\cms\models\post\Comment::model()->getCount(),
			'approved'=>\cms\models\post\Comment::model()->getCount(PostComments::STATUS_APPROVED),
			'unapproved'=>\cms\models\post\Comment::model()->getCount(PostComments::STATUS_UNAPPROVED),
			'pending'=>\cms\models\post\Comment::model()->getCount(PostComments::STATUS_PENDING),
			'deleted'=>\cms\models\post\Comment::model()->getDeletedCount(),
		));
	}
	
	/**
	 * 通过审核
	 */
	public function approve(){
		$id = $this->input->get('id', 'intval');
		
		try{
			Comment::model()->approved($id);
				
			$this->actionlog(Actionlogs::TYPE_POST_COMMENT, '审核通过了一条文章评论', $id);
		
			Response::notify('success', array(
				'data'=>array(
					'id'=>$id,
				),
				'message'=>'一条评论通过审核',
			));
		}catch(Exception $e){
			Response::notify('error', array(
				'data'=>array(
					'id'=>$id,
				),
				'message'=>$e->getMessage(),
				'code'=>$e->getDescription(),
			));
		}
	}
	
	/**
	 * 不通过审核
	 */
	public function unapprove(){
		$id = $this->input->get('id', 'intval');
		
		try{
			Comment::model()->unapproved($id);
		
			$this->actionlog(Actionlogs::TYPE_POST_COMMENT, '审核拒绝了一条文章评论', $id);
		
			Response::notify('success', array(
				'data'=>array(
					'id'=>$id,
				),
				'message'=>'一条评论未通过审核',
			));
		}catch(Exception $e){
			Response::notify('error', array(
				'data'=>array(
					'id'=>$id,
				),
				'message'=>$e->getMessage(),
				'code'=>$e->getDescription(),
			));
		}
	}
	
	/**
	 * 移入回收站
	 */
	public function delete(){
		$id = $this->input->get('id', 'intval');
		
		try{
			Comment::model()->delete($id);
			
			$this->actionlog(Actionlogs::TYPE_POST_COMMENT, '将文章评论移入回收站', $id);
				
			Response::notify('success', array(
				'data'=>array(
					'id'=>$id,
				),
				'message'=>'一条留言被移入回收站 - '.Html::link('撤销', array('admin/post-comment/undelete', array(
					'id'=>$id,
				)))
			));
		}catch(Exception $e){
			Response::notify('error', array(
				'data'=>array(
					'id'=>$id,
				),
				'message'=>$e->getMessage(),
				'code'=>$e->getDescription(),
			));
		}
	}
	
	/**
	 * 移出回收站
	 */
	public function undelete(){
		$id = $this->input->get('id', 'intval');
		
		try{
			Comment::model()->undelete($id);

			$this->actionlog(Actionlogs::TYPE_POST_COMMENT, '将文章评论移出回收站', $id);
				
			Response::notify('success', array(
				'data'=>array(
					'id'=>$id,
				),
				'message'=>'一条留言被移出回收站 - '.Html::link('撤销', array('admin/post-comment/delete', array(
					'id'=>$id,
				))),
			));
		}catch(Exception $e){
			Response::notify('error', array(
				'data'=>array(
					'id'=>$id,
				),
				'message'=>$e->getMessage(),
				'code'=>$e->getDescription(),
			));
		}
	}
	
	/**
	 * 永久删除
	 */
	public function remove(){
		$id = $this->input->get('id', 'intval');
		
		try{
			Comment::model()->remove($id);
			
			$this->actionlog(Actionlogs::TYPE_POST_COMMENT, '永久删除一条文章评论', $id);
			
			Response::notify('success', array(
				'data'=>array(
					'id'=>$id,
				),
				'message'=>'一条留言被永久删除',
			));
		}catch(Exception $e){
			Response::notify('error', array(
				'data'=>array(
					'id'=>$id,
				),
				'message'=>$e->getMessage(),
				'code'=>$e->getDescription(),
			));
		}
	}
	
	public function batch(){
		$ids = $this->input->post('ids', 'intval');
		$action = $this->input->post('batch_action');
		
		switch($action){
			case 'set-approved':
				//通过审核
				$comments = PostComments::model()->fetchAll(array(
					'id IN (?)'=>$ids,
					'status != ' . PostComments::STATUS_APPROVED,
				), 'id,post_id,is_real');
				if(!$comments){
					Response::notify('success', array(
						'message'=>'无符合条件的记录',
					));
				}
				
				$comment_ids = ArrayHelper::column($comments, 'id');
				$affected_rows = PostComments::model()->update(array(
					'status'=>PostComments::STATUS_APPROVED,
				), array(
					'id IN (?)'=>$comment_ids,
				));
				
				$this->actionlog(Actionlogs::TYPE_POST_COMMENT, '批处理：'.$affected_rows.'条文章评论通过审核', $comment_ids);
				
				$post_comment_verify = Option::get('system:post_comment_verify');
				foreach($comments as $c){
					//更新文章评论数
					if($post_comment_verify){
						//如果只显示通过审核的评论，则当评论通过审核时，相应文章评论数+1
						if($c['is_real']){
							Posts::model()->inc('id = '.$c['post_id'], array('comments', 'real_comments'), 1);
						}else{
							Posts::model()->inc('id = '.$c['post_id'], array('comments'), 1);
						}
					}
					
					Hook::getInstance()->call('after_post_comment_approved', array(
						'comment_id'=>$c['id'],
					));
				}
				
				Response::notify('success', $affected_rows.'条评论通过审核');
			break;
			case 'set-unapproved':
			//不通过审核
				
			break;
			case 'set-pending':
			//标记为待审核
				
			break;
			case 'delete':
			//放入回收站
				
			break;
			case 'undelete':
			//从回收站还原
				
			break;
			case 'remove':
			//永久删除
				
			break;
		}
	}
}