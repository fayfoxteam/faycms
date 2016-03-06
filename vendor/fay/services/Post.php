<?php
namespace fay\services;

use fay\core\Model;
use fay\models\tables\Posts;
use fay\models\tables\PostsCategories;
use fay\models\tables\PostsFiles;
use fay\models\tables\Favourites;
use fay\models\tables\PostsTags;
use fay\models\tables\PostPropInt;
use fay\models\tables\PostPropVarchar;
use fay\models\tables\PostPropText;
use fay\models\tables\PostLikes;
use fay\models\tables\PostMeta;
use fay\models\Tag;
use fay\helpers\Request;
use fay\models\Prop;
use fay\models\Post as PostModel;
use fay\models\File;

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
	public function create($post, $extra = array(), $user_id = 0){
		$user_id || $user_id = \F::app()->current_user;
	
		$post['create_time'] = \F::app()->current_time;
		$post['last_modified_time'] = \F::app()->current_time;
		$post['user_id'] = \F::app()->current_user;
		$post['publish_time'] || $post['publish_time'] = \F::app()->current_time;
		$post['publish_date'] = date('Y-m-d', $post['publish_time']);
		$post['ip_int'] = Request::ip2int(\F::app()->ip);
	
		//过滤掉多余的数据
		$post = Posts::model()->fillData($post, false);
		$post_id = Posts::model()->insert($post);
		PostMeta::model()->insert(array(
			'post_id'=>$post_id,
		));
	
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
		//添加到标签表
		if($extra['tags']){
			Tag::model()->set($extra['tags'], $post_id);
		}
	
		//设置附件
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
	 */
	public function update($post_id, $data, $extra = array()){
		$data['last_modified_time'] = \F::app()->current_time;
		//过滤掉多余的数据
		$post = Posts::model()->fillData($data, false);
		Posts::model()->update($post, $post_id);
		$post_meta = PostMeta::model()->fillData($data, false);
		if($post_meta){
			PostMeta::model()->update($post_meta, $post_id);
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
			Tag::model()->set($extra['tags'], $post_id);
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
		//先获取该篇文章对应的tags
		$tag_ids = PostsTags::model()->fetchCol('tag_id', 'post_id = '.$post_id);
		
		//删除文章
		Posts::model()->delete('id = '.$post_id);
		
		//删除文章对应的附加信息
		PostsCategories::model()->delete('post_id = '.$post_id);
		PostsFiles::model()->delete('post_id = '.$post_id);
		PostsTags::model()->delete('post_id = '.$post_id);
		
		//删除文章可能存在的自定义属性
		PostPropInt::model()->delete('post_id = '.$post_id);
		PostPropVarchar::model()->delete('post_id = '.$post_id);
		PostPropText::model()->delete('post_id = '.$post_id);
		
		//删除关注，收藏列表
		PostLikes::model()->delete('post_id = '.$post_id);
		Favourites::model()->delete('post_id = '.$post_id);
		
		//删除文章meta信息
		PostMeta::model()->delete('post_id = ' . $post_id);
		
		//刷新对应tags的count值
		Tag::model()->refreshCountByTagId($tag_ids);
	}
	
}