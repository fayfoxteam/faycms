<?php
namespace glhs\modules\frontend\controllers;

use glhs\library\FrontController;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\Posts;

class SearchController extends FrontController{
	public function index(){
		$keywords = $this->input->get('keywords', 'trim');
		
		$sql = new Sql();
		$sql->from('posts', 'p', 'id,title,abstract,thumbnail,publish_time')
			->joinLeft('categories', 'c', 'p.cat_id = c.id', 'alias AS cat_alias')
			->where(array(
				'p.deleted = 0',
				'p.publish_time < '.$this->current_time,
				'p.status = '.Posts::STATUS_PUBLISHED,
				'p.title LIKE ?'=>'%'.$keywords.'%',
			))
			->order('p.is_top DESC, p.sort, p.publish_time DESC');
		$this->view->assign(array(
			'listview'=>new ListView($sql, array(
				'reload'=>$this->view->url('search/'.$keywords),
				'page_size'=>10,
			)),
			'keywords'=>$keywords,
		))->render();
	}
}