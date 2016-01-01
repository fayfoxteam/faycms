<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\PostComments;
use fay\core\Response;
use fay\models\Setting;

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
}