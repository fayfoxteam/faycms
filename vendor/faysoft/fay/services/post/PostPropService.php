<?php
namespace fay\services\post;

use fay\core\Loader;
use fay\models\PropModel;
use fay\services\CategoryService;
use fay\models\tables\PostsTable;
use fay\models\tables\PropsTable;

class PostPropService extends PropModel{
	/**
	 * @param string $class_name
	 * @return PostPropService
	 */
	public static function service($class_name = __CLASS__){
		return Loader::singleton($class_name);
	}
	
	/**
	 * @see \fay\models\PropModel::$models
	 * @var array
	 */
	protected $models = array(
		'varchar'=>'fay\models\tables\PostPropVarcharTable',
		'int'=>'fay\models\tables\PostPropIntTable',
		'text'=>'fay\models\tables\PostPropTextTable',
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
	protected $type = PropsTable::TYPE_POST_CAT;
	
	/**
	 * @see \fay\models\PropModel::getPropertySet()
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
		$post = PostsTable::model()->find($post_id, 'cat_id');
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
		return $this->getByRefer(CategoryService::service()->getParentIds($cat, '_system_post'));
	}
	
	/**
	 * 将props信息装配到$posts中
	 * @param array $posts 包含文章信息的三维数组
	 *   若包含$posts.post.id字段，则以此字段作为文章ID
	 *   若不包含$posts.post.id，则以$posts的键作为文章ID
	 * @param null|string $fields 属性列表
	 */
	public function assemble(&$posts, $fields = null){
		if(in_array('*', $fields['fields'])){
			$props = null;
		}else{
			$props = $this->mget($fields);
		}
		
		foreach($posts as $k => $p){
			if(isset($p['post']['id'])){
				$post_id = $p['post']['id'];
			}else{
				$post_id = $k;
			}
			
			$p['props'] = $this->getPropertySet($post_id, $props);
			
			$posts[$k] = $p;
		}
	}
}