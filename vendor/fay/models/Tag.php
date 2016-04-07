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
			->joinLeft(array('tc'=>'tag_counter'), 't.id = tc.tag_id', TagCounter::model()->getFields(array('tag_id')))
			->where('t.status = ' . Tags::STATUS_ENABLED)
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
	 * @param array $conditions 附加条件（例如编辑标签的时候，判断重复需要传入id != tag_id的条件）
	 * @return 若存在，返回标签ID，若不存在，返回false
	 */
	public static function isTagExist($title, $conditions = array()){
		if($title){
			$tag = Tags::model()->fetchRow(array(
				'title = ?'=>$title,
			) + $conditions, 'id');
			if($tag){
				return $tag['id'];
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 递增一个或多个指定标签的计数
	 * @param array|int $tag_ids
	 * @param strin $field tag_counter表对应的列名
	 * @param int $value 增量，默认为1，可以是负数
	 */
	public function incr($tag_ids, $field, $value = 1){
		if(!$tag_ids){
			return 0;
		}
		if(!is_array($tag_ids)){
			$tag_ids = array($tag_ids);
		}
		
		return TagCounter::model()->incr(array(
			'tag_id IN (?)'=>$tag_ids,
		), $field, $value);
	}
	
	/**
	 * 递减一个或多个指定标签的计数
	 * @param array|int $tag_ids
	 * @param strin $field tag_counter表对应的列名
	 * @param int $value 增量，默认为-1，可以是正数
	 */
	public function decr($tag_ids, $field, $value = -1){
		return $this->incr($tag_ids, $field, $value);
	}
}