<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Tags;
use fay\core\Sql;
use fay\common\ListView;

class Tag extends Model{
	/**
	 * @return Tag
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function getList($type, $page_size = 20, $page = 1, $sort = '{$type}_count DESC'){
		$sql = new Sql();
		$sql->from('tags', 't', 'id,title,post_count,feed_count')
			->where('status = ' . Tags::STATUS_ENABLED)
			->order(str_replace('{$type}', $type, $sort));
		$listview = new ListView($sql, array(
			'page_size'=>$page_size,
			'current_page'=>$page,
		));
		
		return array(
			'tags'=>$listview->getData(),
			'pager'=>$listview->getPager(),
		);
	}
	
	/**
	 * 判断一个标签是否存在（禁用的标签也视为存在）
	 * @param string $title
	 */
	public static function isTagExist($title){
		if($title){
			$tag = Tags::model()->fetchRow(array(
				'title = ?'=>$title,
			), 'id');
			if($tag){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}