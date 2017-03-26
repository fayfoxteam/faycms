<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\PostCommentsTable;
use fay\core\Response;
use fay\models\tables\ActionlogsTable;
use fay\helpers\HtmlHelper;
use fay\services\post\PostCommentService;
use fay\core\Exception;

class PostCommentController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'message';
	}
	
	public function index(){
		$this->layout->subtitle = '文章评论';
		
		//页面设置
		$this->settingForm('admin_post_comment_index', '_setting_index', array(
			'cols'=>array('user', 'content', 'post', 'status', 'create_time'),
			'display_name'=>'username',
			'page_size'=>20,
		));
		
		$sql = new Sql();
		$sql->from(array('pc'=>'post_comments'))
			->joinLeft(array('p'=>'posts'), 'pc.post_id = p.id', 'title AS post_title')
			->joinLeft(array('u'=>'users'), 'pc.user_id = u.id', 'realname,username,nickname')
			->order('id DESC')
		;
		if($this->input->get('deleted')){
			$sql->where(array(
				'pc.delete_time > 0',
			));
		}else if($this->input->get('status', 'intval') !== null && $this->input->get('deleted', 'intval') != 1){
			$sql->where(array(
				'pc.status = ?'=>$this->input->get('status', 'intval'),
				'pc.delete_time = 0',
			));
		}else{
			$sql->where('pc.delete_time = 0');
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
			'empty_text'=>'<tr><td colspan="'.(count($this->form('setting')->getData('cols')) + 2).'" align="center">无相关记录！</td></tr>',
		));
		$this->view->listview = $listview;
		
		$this->view->render();
	}
	
	/**
	 * 返回各状态下的文章评论数
	 */
	public function getCounts(){
		Response::json(array(
			'all'=>\cms\services\post\PostCommentService::service()->getCount(),
			'approved'=>\cms\services\post\PostCommentService::service()->getCount(PostCommentsTable::STATUS_APPROVED),
			'unapproved'=>\cms\services\post\PostCommentService::service()->getCount(PostCommentsTable::STATUS_UNAPPROVED),
			'pending'=>\cms\services\post\PostCommentService::service()->getCount(PostCommentsTable::STATUS_PENDING),
			'deleted'=>\cms\services\post\PostCommentService::service()->getDeletedCount(),
		));
	}
	
	/**
	 * 通过审核
	 */
	public function approve(){
		$id = $this->input->get('id', 'intval');
		
		try{
			PostCommentService::service()->approve($id);
				
			$this->actionlog(ActionlogsTable::TYPE_POST_COMMENT, '审核通过了一条文章评论', $id);
		
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
	public function disapprove(){
		$id = $this->input->get('id', 'intval');
		
		try{
			PostCommentService::service()->disapprove($id);
		
			$this->actionlog(ActionlogsTable::TYPE_POST_COMMENT, '审核拒绝了一条文章评论', $id);
		
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
			PostCommentService::service()->delete($id);
			
			$this->actionlog(ActionlogsTable::TYPE_POST_COMMENT, '将文章评论移入回收站', $id);
				
			Response::notify('success', array(
				'data'=>array(
					'id'=>$id,
				),
				'message'=>'一条留言被移入回收站 - '.HtmlHelper::link('撤销', array('admin/post-comment/undelete', array(
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
			PostCommentService::service()->undelete($id);

			$this->actionlog(ActionlogsTable::TYPE_POST_COMMENT, '将文章评论移出回收站', $id);
				
			Response::notify('success', array(
				'data'=>array(
					'id'=>$id,
				),
				'message'=>'一条留言被移出回收站 - '.HtmlHelper::link('撤销', array('admin/post-comment/delete', array(
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
			PostCommentService::service()->remove($id);
			
			$this->actionlog(ActionlogsTable::TYPE_POST_COMMENT, '永久删除一条文章评论', $id);
			
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
				$affected_rows = PostCommentService::service()->batchApprove($ids);
				if($affected_rows){
					Response::notify('success', $affected_rows.'条评论通过审核');
				}else{
					Response::notify('success', array(
						'message'=>'无符合条件的记录',
					));
				}
			break;
			case 'set-disapproved':
				//不通过审核
				$affected_rows = PostCommentService::service()->batchDisapprove($ids);
				if($affected_rows){
					Response::notify('success', $affected_rows.'条评论未通过审核');
				}else{
					Response::notify('success', array(
						'message'=>'无符合条件的记录',
					));
				}
			break;
			case 'set-pending':
				//标记为待审核
				
			break;
			case 'delete':
				//放入回收站
				$affected_rows = PostCommentService::service()->batchDelete($ids);
				if($affected_rows){
					Response::notify('success', $affected_rows.'条评论被删除');
				}else{
					Response::notify('success', array(
						'message'=>'无符合条件的记录',
					));
				}
			break;
			case 'undelete':
				//从回收站还原
				$affected_rows = PostCommentService::service()->batchUnelete($ids);
				if($affected_rows){
					Response::notify('success', $affected_rows.'条评论被还原');
				}else{
					Response::notify('success', array(
						'message'=>'无符合条件的记录',
					));
				}
			break;
			case 'remove':
			//永久删除
				
			break;
			default:
				Response::notify('error', array(
					'message'=>'未选择操作',
				));
			break;
		}
	}
}