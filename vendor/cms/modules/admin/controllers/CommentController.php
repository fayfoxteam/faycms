<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\models\tables\Messages;
use fay\common\ListView;

class CommentController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'message';
	}
	
	public function index(){
		$this->layout->subtitle = '文章评论';
		
		$sql = new Sql();
		$sql->from(array('m'=>'messages'))
			->joinLeft(array('p'=>'posts'), 'm.target = p.id', 'title AS post_title, id AS post_id')
			->joinLeft(array('u'=>'users'), 'm.user_id = u.id', 'realname,username,nickname')
			->where('m.type = '.Messages::TYPE_POST_COMMENT)
			->order('id DESC')
		;
		
		if($this->input->get('deleted')){
			$sql->where(array(
				'm.deleted = 1',
			));
		}else if($this->input->get('status') !== null && $this->input->get('status') !== ''){
			$sql->where(array(
				'm.status = ?'=>$this->input->get('status', 'intval'),
				'm.deleted = 0',
			));
		}else{
			$sql->where('m.deleted = 0');
		}
		
		$listview = new ListView($sql, array(
			'page_size'=>30,
			'empty_text'=>'<tr><td colspan="5" align="center">无相关记录！</td></tr>',
		));
		$this->view->listview = $listview;
		
		$this->view->render();
	}
}