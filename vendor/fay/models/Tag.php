<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Tags;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\TagCounter;

class Tag extends Model{
	/**
	 * @return Tag
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取标签列表
	 * @param string $order 排序方式（例如t.sort这样完整的带表别名前缀的字段）
	 * @param int $page_size
	 * @param int $page
	 */
	public function getList($order, $page_size = 20, $page = 1){
		$sql = new Sql();
		$sql->from(array('t'=>'tags'), 'id,title')
			->joinLeft(array('tc'=>'tag_counter'), 't.id = tc.tag_id', TagCounter::model()->getFields('tag_id'))
			->where('status = ' . Tags::STATUS_ENABLED)
			->order($order);
		;
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
	 * @return 若存在，返回标签ID，若不存在，返回false
	 */
	public static function isTagExist($title){
		if($title){
			$tag = Tags::model()->fetchRow(array(
				'title = ?'=>$title,
			), 'id');
			if($tag){
				return $tag['id'];
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}