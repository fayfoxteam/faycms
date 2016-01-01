<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\common\ListView;

class PostCommentController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'message';
	}
	
	public function index(){
		$this->layout->subtitle = '文章评论';
		
		$sql = new Sql();
		$sql->from('post_comments', 'pc')
			->joinLeft('posts', 'p', 'p.post_id = p.id', 'title AS post_title')
			->joinLeft('users', 'u', 'pc.user_id = u.id', 'realname,username,nickname')
			->order('id DESC')
		;
		if($this->input->get('deleted')){
			$sql->where(array(
				'pc.deleted = 1',
			));
		}else if($this->input->get('status') !== null && $this->input->get('status') !== ''){
			$sql->where(array(
				'pc.status = ?'=>$this->input->get('status', 'intval'),
				'pc.deleted = 0',
			));
		}else{
			$sql->where('pc.deleted = 0');
		}
		
		$listview = new ListView($sql, array(
			'page_size'=>30,
			'empty_text'=>'<tr><td colspan="5" align="center">无相关记录！</td></tr>',
		));
		$this->view->listview = $listview;			
		
		$this->view->render();
	}
}