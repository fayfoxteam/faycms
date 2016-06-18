<?php
namespace fay\models\post;

use fay\models\Category;
use fay\models\tables\Posts;
use fay\models\tables\Props;

class Prop extends \fay\models\Prop{
	/**
	 * @param string $class_name
	 * @return Prop
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * @see Prop::$models
	 * @var array
	 */
	protected $models = array(
		'varchar'=>'fay\models\tables\PostPropVarchar',
		'int'=>'fay\models\tables\PostPropInt',
		'text'=>'fay\models\tables\PostPropText',
	);
	
	/**
	 * @see Prop::$foreign_key
	 * @var string
	 */
	protected $foreign_key = 'post_id';
	
	/**
	 * @see Prop::$type
	 * @var string
	 */
	protected $type = Props::TYPE_POST_CAT;
	
	/**
	 * @see \fay\models\Prop::getPropertySet()
	 * @param int $post_id 文章ID
	 * @param null|array $props 属性列表
	 * @return array
	 */
	public function getPropertySet($post_id, $props = null){
		if($props === null){
			$props = $this->getProps($post_id);
		}
		
		return parent::getPropertySet($post_id, $props);
	}
	
	/**
	 * 根据文章ID，获取文章对应属性（不带属性值）
	 * @param int $post_id
	 * @return array
	 */
	public function getProps($post_id){
		$post = Posts::model()->find($post_id, 'cat_id');
		return $this->getPropsByCat($post['cat_id']);
	}
	
	/**
	 * 根据分类ID，获取相关属性（不带属性值）
	 * @param int $cat
	 *  - 数字:代表分类ID;
	 *  - 字符串:分类别名;
	 *  - 数组:分类数组（节约服务器资源，少一次数据库搜索。必须包含left_value和right_value字段）
	 * @return array
	 */
	public function getPropsByCat($cat){
		return Prop::model()->getByRefer(Category::model()->getParentIds($cat, '_system_post'));
	}
}