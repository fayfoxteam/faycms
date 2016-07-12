<?php
namespace fay\services\post;

use fay\core\Service;
use fay\core\Sql;
use fay\helpers\ArrayHelper;
use fay\helpers\StringHelper;
use fay\models\tables\Posts;
use fay\models\tables\TagCounter;
use fay\models\tables\Tags;
use fay\models\tables\PostsTags;
use fay\services\Tag as TagService;

class Tag extends Service{
	/**
	 * 默认返回字段
	 */
	private $default_fields = array('id', 'title');
	
	/**
	 * @param string $class_name
	 * @return Tag
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 获取文章对应tags
	 * @param int $post_id 文章ID
	 * @param string $fields 标签字段，tags表字段
	 * @return array 返回包含文章tag信息的二维数组
	 */
	public function get($post_id, $fields = null){
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = $this->default_fields;
		}
		$sql = new Sql();
		return $sql->from(array('pt'=>'posts_tags'), '')
			->joinLeft(array('t'=>'tags'), 'pt.tag_id = t.id', Tags::model()->formatFields($fields))
			->where(array(
				'pt.post_id = ?'=>$post_id,
			))
			->fetchAll();
	}
	
	/**
	 * 批量获取文章对应tags
	 * @param array $post_ids 文章ID构成的二维数组
	 * @param string $fields 标签字段，tags表字段
	 * @return array 返回以文章ID为key的三维数组
	 */
	public function mget($post_ids, $fields = null){
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = $this->default_fields;
		}
		$sql = new Sql();
		$tags = $sql->from(array('pt'=>'posts_tags'), 'post_id')
			->joinLeft(array('t'=>'tags'), 'pt.tag_id = t.id', Tags::model()->formatFields($fields))
			->where(array('pt.post_id IN (?)'=>$post_ids))
			->fetchAll();
		$return = array_fill_keys($post_ids, array());
		foreach($tags as $t){
			$p = $t['post_id'];
			unset($t['post_id']);
			$return[$p][] = $t;
		}
		return $return;
	}
	
	/**
	 * 根据一个或多个文章ID，返回文章对应标签的ID
	 * @param int|array|string $post_ids 文章ID，或文章ID构成的一维数组或逗号分割的字符串
	 * @return array 标签ID构成的一维数组（可能重复）
	 */
	public function getTagIds($post_ids){
		if(!$post_ids){
			return array();
		}
		
		if(StringHelper::isInt($post_ids)){
			//单个ID
			return PostsTags::model()->fetchCol('tag_id', array(
				'post_id = ?'=>$post_ids,
			));
		}else{
			if(is_string($post_ids)){
				//逗号分割的ID串
				$post_ids = explode(',', $post_ids);
			}
			
			return PostsTags::model()->fetchCol('tag_id', array(
				'post_id IN (?)'=>$post_ids,
			));
		}
	}
	
	/**
	 * 根据tag_id刷新该tag的count种值
	 * @param int|array $tag_ids 可以传入单个ID或一维数组
	 */
	public function refreshCountByTagId($tag_ids){
		if(!is_array($tag_ids)){
			$tag_ids = array($tag_ids);
		}
		
		if($tag_ids){
			$sql = new Sql();
			$posts_tags = $sql->from(array('pt'=>'posts_tags'), 'COUNT(*) AS count, tag_id')
				->joinLeft(array('p'=>'posts'), 'pt.post_id = p.id')
				->where(array(
					'pt.tag_id IN (?)'=>$tag_ids,
					'p.deleted = 0',
					'p.status = '.Posts::STATUS_PUBLISHED,
				))//这里不限制publish_time条件，因为定时发布后没逻辑来更新标签对应文章数
				->group('pt.tag_id')
				->fetchAll();
			$posts_tags = ArrayHelper::column($posts_tags, 'count', 'tag_id');
			foreach($tag_ids as $tag){
				TagCounter::model()->update(array(
					'posts'=>empty($posts_tags[$tag]) ? 0 : $posts_tags[$tag],
				), $tag);
			}
		}
	}
	
	/**
	 * 根据文章ID，刷新该文章对应的tags的count值
	 * @param int|array $post_id 可以传入单个ID或一维数组
	 */
	public function refreshCountByPostId($post_id){
		if(!is_array($post_id)){
			$post_id = array($post_id);
		}
		
		$tag_ids = PostsTags::model()->fetchCol('tag_id', array(
			'post_id IN (?)'=>$post_id,
		));
		
		$this->refreshCountByTagId($tag_ids);
	}
	
	/**
	 * 递增一篇文章相关的标签文章数
	 * @param int|array|string $post_ids 文章ID，或由文章ID构成的数组或逗号分割字符串
	 * @return bool
	 */
	public function incr($post_ids){
		if(!$post_ids){
			return false;
		}
		
		$tag_ids = $this->getTagIds($post_ids);
		
		$count_map = ArrayHelper::countValues($tag_ids);
		foreach($count_map as $num => $sub_tag_ids){
			TagService::service()->incr($sub_tag_ids, 'posts', $num);
		}
		
		return true;
	}
	
	/**
	 * 递减一篇文章相关的标签文章数
	 * @param int|array|string $post_ids 文章ID，或由文章ID构成的数组或逗号分割字符串
	 * @return bool
	 */
	public function decr($post_ids){
		if(!$post_ids){
			return false;
		}
		
		$tag_ids = $this->getTagIds($post_ids);
		
		$count_map = ArrayHelper::countValues($tag_ids);
		foreach($count_map as $num => $sub_tag_ids){
			TagService::service()->decr($sub_tag_ids, 'posts', -$num);
		}
		
		return true;
	}
	
	/**
	 * 设置一篇文章的标签
	 * @param string|array $tags 逗号分割的标签文本，或由标签文本构成的一维数组。若为空，则删除指定文章的所有标签
	 * @param int $post_id 文章ID
	 * @param int|null $old_status 文章原状态
	 * @param int|null $new_status 文章新状态
	 */
	public function set($tags, $post_id, $old_status, $new_status){
		if($tags){
			if(!is_array($tags)){
				$tags = explode(',', $tags);
			}
		}else{
			$tags = array();
		}
		
		//获取输入标签的ID
		$input_tag_ids = array();
		foreach($tags as $tag_title){
			if(!$tag_title = trim($tag_title))continue;
			$tag = Tags::model()->fetchRow(array(
				'title = ?'=>$tag_title,
			), 'id');
			if($tag){//已存在，获取id
				$input_tag_ids[] = $tag['id'];
			}else{//不存在，插入新tag
				$input_tag_ids[] = TagService::service()->create($tag_title);
			}
		}
		
		$old_tag_ids = array();
		$deleted_tag_ids = array();
		if($old_status !== null){
			//原状态非null，说明是编辑文章，需要获取文章原标签，删掉已经被删掉的标签
			$old_tag_ids = PostsTags::model()->fetchCol('tag_id', array(
				'post_id = ?'=>$post_id,
			));
			
			//删除已被删除的标签
			$deleted_tag_ids = array_diff($old_tag_ids, $input_tag_ids);
			if($deleted_tag_ids){
				PostsTags::model()->delete(array(
					'post_id = ?'=>$post_id,
					'tag_id IN (?)'=>$deleted_tag_ids
				));
			}
		}
		
		//插入新的标签
		if($old_tag_ids){
			$new_tag_ids = array_diff($input_tag_ids, $old_tag_ids);
		}else{
			$new_tag_ids = $input_tag_ids;
		}
		if($new_tag_ids){
			foreach($new_tag_ids as $v){
				PostsTags::model()->insert(array(
					'post_id'=>$post_id,
					'tag_id'=>$v,
				));
			}
		}
		
		if($old_status === null && $new_status == Posts::STATUS_PUBLISHED){
			//没有原状态，说明是新增文章，且文章状态为已发布：所有输入标签文章数加一
			TagService::service()->incr($input_tag_ids, 'posts');
		}else if($old_status == Posts::STATUS_PUBLISHED && $new_status != Posts::STATUS_PUBLISHED){
			//本来处于已发布状态，编辑后变成未发布：文章原标签文章数减一
			TagService::service()->decr($old_tag_ids, 'posts');
		}else if($old_status != Posts::STATUS_PUBLISHED && $new_status == Posts::STATUS_PUBLISHED){
			//本来是未发布状态，编辑后变成已发布：所有输入标签文章数加一
			TagService::service()->incr($input_tag_ids, 'posts');
		}else if($old_status == Posts::STATUS_PUBLISHED && $new_status == Posts::STATUS_PUBLISHED){
			//本来是已发布状态，编辑后还是已发布状态：新增标签文章数加一，被删除标签文章数减一
			if($new_tag_ids){
				TagService::service()->incr($new_tag_ids, 'posts');
			}
			if($deleted_tag_ids){
				TagService::service()->decr($deleted_tag_ids, 'posts');
			}
		}else if($old_status == Posts::STATUS_PUBLISHED && $new_status === null){
			//本来是已发布状态，编辑时并未编辑状态：新增标签文章数加一，被删除标签文章数减一
			if($new_tag_ids){
				TagService::service()->incr($new_tag_ids, 'posts');
			}
			if($deleted_tag_ids){
				TagService::service()->decr($deleted_tag_ids, 'posts');
			}
		}
	}
}