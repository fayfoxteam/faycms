<?php
namespace church\modules\frontend\controllers;

use church\library\FrontController;
use fay\core\HttpException;
use fay\models\tables\TagsTable;

class TagController extends FrontController{
	public function item(){
		$tag_title = $this->input->get('tag_title', 'trim');
		if(!$tag_title || !$tag = TagsTable::model()->fetchRow(array(
				'title = ?'=>$tag_title
			))){
			throw new HttpException('您请求的页面不存在');
		}
		
		$this->layout->assign(array(
			'page_title'=>$tag['title'],
		));
		
		$this->view->render();
	}
}