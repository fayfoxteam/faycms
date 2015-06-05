<?php
namespace jxsj\modules\frontend\controllers;

use jxsj\library\FrontController;
use fay\models\Category;
use fay\core\Sql;
use fay\models\tables\Posts;
use fay\common\ListView;
use fay\core\HttpException;

class CatController extends FrontController{
	public function index(){
		$cat = Category::model()->get($this->input->get('id', 'intval'));
		
		if(!$cat){
			throw new HttpException('页面不存在');
		}
		
		$this->layout->title = $cat['seo_title'] ? $cat['seo_title'] : $cat['title'];
		$this->layout->keywords = $cat['seo_keywords'] ? $cat['seo_keywords'] : $cat['title'];
		$this->layout->description = $cat['seo_description'];
		
		$this->view->cat = $cat;
		
		$sql = new Sql();
		$sql->from('posts', 'p', 'id,title,publish_time,thumbnail,content')
			->joinLeft('categories', 'c', 'p.cat_id = c.id')
			->order('p.is_top DESC, p.sort, p.publish_time DESC')
			->where(array(
				'c.left_value >= '.$cat['left_value'],
				'c.right_value <= '.$cat['right_value'],
				'p.deleted = 0',
				'p.status = '.Posts::STATUS_PUBLISHED,
				'p.publish_time < '.$this->current_time,
			))
		;
		$this->view->listview = new ListView($sql, array(
			'page_size'=>12,
			'reload'=>$this->view->url('cat/'.$cat['id']),
			'item_view'=>$cat['description'] == 'gallery' ? '_gallery_item' : '_list_item',
		));
				
		$this->view->render();
	}
	
}