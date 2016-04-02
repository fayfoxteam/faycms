<?php
namespace fay\services;

use fay\core\Model;
use fay\models\tables\Posts;
use fay\models\tables\PostsCategories;
use fay\models\tables\PostsFiles;
use fay\models\tables\PostsTags;
use fay\models\tables\PostPropInt;
use fay\models\tables\PostPropVarchar;
use fay\models\tables\PostPropText;
use fay\models\tables\PostLikes;
use fay\models\tables\PostMeta;
use fay\models\post\Tag as PostTagModel;
use fay\helpers\Request;
use fay\models\Prop;
use fay\models\Post as PostModel;
use fay\models\File;
use fay\models\tables\UserCounter;
use fay\services\post\Tag as PostTagService;
use fay\core\Hook;
use fay\models\tables\PostFavorites;

/**
 * 文章服务
 */
class Post extends Model{

	/**
	 * @return Post
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 创建一篇文章
	 * @param array $post posts表相关字段
	 * @param array $extra 其它字段
	 *   - categories 附加分类ID，逗号分隔或一维数组
	 *   - tags 标签文本，逗号分割或一维数组
	 *   - files 由文件ID为键，文件描述为值构成的关联数组
	 *   - props 以属性ID为键，属性值为值构成的关联数组
	 * @param int $user_id 作者ID
	 */
	public function create($post, $extra = array(), $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		
		$post['create_time'] = \F::app()->current_time;
		$post['last_modified_time'] = \F::app()->current_time;
		$post['user_id'] = $user_id;
		empty($post['publish_time']) && $post['publish_time'] = \F::app()->current_time;
		$post['publish_date'] = date('Y-m-d', $post['publish_time']);
		$post['ip_int'] = Request::ip2int(\F::app()->ip);
		
		//过滤掉多余的数据
		$post_id = Posts::model()->insert($post, true);
		
		$post_meta = array(
			'post_id'=>$post_id,
		);
		if(isset($extra['meta'])){
			$post_meta = $post_meta + $extra['meta'];
		}
		
		PostMeta::model()->insert($post_meta);
		
		//文章分类
		if(!empty($extra['categories'])){
			if(!is_array($extra['categories'])){
				$extra['categories'] = explode(',', $extra['categories']);
			}
			foreach($extra['categories'] as $cat_id){
				PostsCategories::model()->insert(array(
					'post_id'=>$post_id,
					'cat_id'=>$cat_id,
				));
			}
		}
		//标签
		if($extra['tags']){
			PostTagService::model()->set($extra['tags'], $post_id);
		}
		
		//附件
		if($extra['files']){
			$i = 0;
			foreach($extra['files'] as $file_id => $description){
				$i++;
				PostsFiles::model()->insert(array(
					'file_id'=>$file_id,
					'post_id'=>$post_id,
					'description'=>$description,
					'is_image'=>File::isImage($file_id),
					'sort'=>$i,
				));
			}
		}
		
		//设置属性
		if($extra['props']){
			$this->createPropertySet($post_id, $extra['props']);
		}
		
		if(isset($post['status'])){
			//如果有传入文章状态，且文章状态为“已发布”，用户文章数加一
			if($post['status'] == Posts::STATUS_PUBLISHED){
				//用户文章数加一
				UserCounter::model()->incr($user_id, 'posts', 1);
				
				//相关标签文章数加一
				PostTagModel::model()->incr($post_id);
			}
		}else{
			//如果未传入status，获取文章状态进行判断
			$post = Posts::model()->find($post_id, 'status');
			if($post['status'] == Posts::STATUS_PUBLISHED){
				//用户文章数加一
				UserCounter::model()->incr($user_id, 'posts', 1);
				
				//相关标签文章数加一
				PostTagModel::model()->incr($post_id);
			}
		}
			
		//hook
		Hook::getInstance()->call('after_post_created', array(
			'post_id'=>$post_id,
		));
		
		return $post_id;
	}
	
	/**
	 * 更新一篇文章
	 * @param int $post_id 文章ID
	 * @param array $post posts表相关字段
	 * @param array $extra 其它字段
	 *   - categories 附加分类ID，逗号分隔或一维数组。若不传，则不会更新，若传了空数组，则清空附加分类。
	 *   - tags 标签文本，逗号分割或一维数组。若不传，则不会更新，若传了空数组，则清空标签。
	 *   - files 由文件ID为键，文件描述为值构成的关联数组。若不传，则不会更新，若传了空数组，则清空附件。
	 *   - props 以属性ID为键，属性值为值构成的关联数组。若不传，则不会更新，若传了空数组，则清空属性。
	 * @param bool $update_last_modified_time 是否更新“最后更新时间”。默认为true
	 */
	public function update($post_id, $data, $extra = array(), $update_last_modified_time = true){
		//获取原文章
		$old_post = Posts::model()->find($post_id, 'user_id,deleted,status');
		if(!$old_post){
			return false;
		}
		
		if($update_last_modified_time){
			$data['last_modified_time'] = \F::app()->current_time;
		}
		
		if(isset($data['deleted'])){
			//更新的时候，不允许修改deleted字段，删除有专门的删除服务
			unset($data['deleted']);
		}
		
		//过滤掉多余的数据
		Posts::model()->update($data, $post_id, true);
		
		//计数表
		if(!empty($extra['meta'])){
			PostMeta::model()->update($extra['meta'], $post_id, true);
		}
		
		//若原文章未删除，更新用户及标签的文章数
		if(!$old_post['deleted']){
			if($old_post['status'] == Posts::STATUS_PUBLISHED &&
				isset($data['status']) && $data['status'] != Posts::STATUS_PUBLISHED){
				//若原文章是“已发布”状态，且新状态不是“已发布”
				UserCounter::model()->incr($old_post['user_id'], 'posts', -1);
		
				//相关标签文章数减一
				PostTagModel::model()->decr($post_id);
			}else if($old_post['status'] != Posts::STATUS_PUBLISHED &&
				isset($data['status']) && $data['status'] == Posts::STATUS_PUBLISHED){
				//若原文章不是“已发布”状态，且新状态是“已发布”
				UserCounter::model()->incr($old_post['user_id'], 'posts', 1);
		
				//相关标签文章数加一
				PostTagModel::model()->incr($post_id);
			}
		}
		
		//附加分类
		if(isset($extra['categories'])){
			if(!is_array($extra['categories'])){
				$extra['categories'] = explode(',', $extra['categories']);
			}
			$post = Posts::model()->find($post_id, 'cat_id');
			if(!empty($extra['categories'])){
				//删除被删除了的分类
				PostsCategories::model()->delete(array(
					'post_id = ?'=>$post_id,
					'or'=>array(
						'cat_id NOT IN (?)'=>$extra['categories'],
						'cat_id = ?'=>$post['cat_id'],//主属性不应出现在附加属性中
					),
				));
				foreach($extra['categories'] as $cat_id){
					if(!PostsCategories::model()->fetchRow(array(
						'post_id = ?'=>$post_id,
						'cat_id = ?'=>$cat_id,
					))){
						//不存在，则插入
						PostsCategories::model()->insert(array(
							'post_id'=>$post_id,
							'cat_id'=>$cat_id,
						));
					}
				}
			}else{
				//删除全部附加分类
				PostsCategories::model()->delete(array(
					'post_id = ?'=>$post_id,
				));
			}
		}
		
		//标签
		if(isset($extra['tags'])){
			PostTagService::model()->set($extra['tags'], $post_id);
		}
		
		//附件
		if(isset($extra['files'])){
			//删除已被删除的图片
			if($extra['files']){
				PostsFiles::model()->delete(array(
					'post_id = ?'=>$post_id,
					'file_id NOT IN (?)'=>array_keys($extra['files']),
				));
			}else{
				PostsFiles::model()->delete(array(
					'post_id = ?'=>$post_id,
				));
			}
			//获取已存在的图片
			$old_files_ids = PostsFiles::model()->fetchCol('file_id', array(
				'post_id = ?'=>$post_id,
			));
			$i = 0;
			foreach($extra['files'] as $file_id => $description){
				$i++;
				if(in_array($file_id, $old_files_ids)){
					PostsFiles::model()->update(array(
						'description'=>$description,
						'sort'=>$i,
					), array(
						'post_id = ?'=>$post_id,
						'file_id = ?'=>$file_id,
					));
				}else{
					PostsFiles::model()->insert(array(
						'post_id'=>$post_id,
						'file_id'=>$file_id,
						'description'=>$description,
						'sort'=>$i,
						'is_image'=>File::isImage($file_id),
					));
				}
			}
		}
	
		//附加属性
		if(isset($extra['props'])){
			$this->updatePropertySet($post_id, $extra['props']);
		}
		
		//hook
		Hook::getInstance()->call('after_post_updated', array(
			'post_id'=>$post_id,
		));
		
		return true;
	}
	
	/**
	 * 新增一个文章属性集
	 * @param int $post_id 文章ID
	 * @param array $data 以属性ID为键的属性键值数组
	 * @param null|array $props 属性。若为null，则根据文章ID获取属性
	 */
	public function createPropertySet($post_id, $data, $props = null){
		if($props === null){
			$props = PostModel::model()->getProps($post_id);
		}
		Prop::model()->createPropertySet('post_id', $post_id, $props, $data, array(
			'varchar'=>'fay\models\tables\PostPropVarchar',
			'int'=>'fay\models\tables\PostPropInt',
			'text'=>'fay\models\tables\PostPropText',
		));
	}
	
	/**
	 * 更新一个文章属性集
	 * @param int $post_id 文章ID
	 * @param array $data 以属性ID为键的属性键值数组
	 * @param null|array $props 属性。若为null，则根据文章ID获取属性
	 */
	public function updatePropertySet($post_id, $data, $props = null){
		if($props === null){
			$props = PostModel::model()->getProps($post_id);
		}
		Prop::model()->updatePropertySet('post_id', $post_id, $props, $data, array(
			'varchar'=>'fay\models\tables\PostPropVarchar',
			'int'=>'fay\models\tables\PostPropInt',
			'text'=>'fay\models\tables\PostPropText',
		));
	}
	
	/**
	 * 彻底删除一篇文章
	 */
	public function remove($post_id){
		//获取文章删除状态
		$post = Posts::model()->find($post_id, 'user_id,deleted,status');
		if(!$post){
			return false;
		}
		
		//执行钩子
		Hook::getInstance()->call('before_post_removed', array(
			'post_id'=>$post_id,
		));
		
		//删除文章
		Posts::model()->delete($post_id);
		
		//若文章未通过回收站被直接删除，且文章“已发布”
		if(!$post['deleted'] && $post['status'] == Posts::STATUS_PUBLISHED){
			//则作者文章数减一
			UserCounter::model()->incr($post['user_id'], 'posts', -1);
			
			//相关标签文章数减一
			PostTagModel::model()->decr($post_id);
		}
		//删除文章与标签的关联关系
		PostsTags::model()->delete('post_id = ' . $post_id);
		
		//删除文章附加分类
		PostsCategories::model()->delete('post_id = '.$post_id);
		
		//删除文章附件（只是删除对应关系，并不删除附件文件）
		PostsFiles::model()->delete('post_id = '.$post_id);
		
		//删除文章可能存在的自定义属性
		PostPropInt::model()->delete('post_id = '.$post_id);
		PostPropVarchar::model()->delete('post_id = '.$post_id);
		PostPropText::model()->delete('post_id = '.$post_id);
		
		//删除关注，收藏列表
		PostLikes::model()->delete('post_id = '.$post_id);
		PostFavorites::model()->delete('post_id = '.$post_id);
		
		//删除文章meta信息
		PostMeta::model()->delete('post_id = ' . $post_id);
	}
	
	/**
	 * 删除一篇文章
	 * @param int $post_id 文章ID
	 */
	public function delete($post_id){
		$post = Posts::model()->find($post_id, 'user_id,deleted');
		if(!$post || $post['deleted']){
			return false;
		}
		
		//标记为已删除
		Posts::model()->update(array(
			'deleted'=>1
		), $post_id);
		
		//若被删除文章是“已发布”状态
		if($post['status'] == Posts::STATUS_PUBLISHED){
			//用户文章数减一
			UserCounter::model()->incr($post['user_id'], 'posts', -1);
			
			//相关标签文章数减一
			PostTagModel::model()->decr($post_id);
		}
		
		//执行钩子
		Hook::getInstance()->call('after_post_deleted', array(
			'post_id'=>$post_id,
		));
		
		return true;
	}
	
	/**
	 * 还原一篇文章
	 * @param int $post_id 文章ID
	 */
	public function undelete($post_id){
		$post = Posts::model()->find($post_id, 'user_id,deleted');
		if(!$post || !$post['deleted']){
			return false;
		}
		
		//标记为未删除
		Posts::model()->update(array(
			'deleted'=>0
		), $post_id);
		
		//若被还原文章是“已发布”状态
		if($post['status'] == Posts::STATUS_PUBLISHED){
			//用户文章数减一
			UserCounter::model()->incr($post['user_id'], 'posts', 1);
			
			//相关标签文章数加一
			PostTagModel::model()->incr($post_id);
		}
		
		//执行钩子
		Hook::getInstance()->call('after_post_undeleted', array(
			'post_id'=>$post_id,
		));
		
		return true;
	}
}