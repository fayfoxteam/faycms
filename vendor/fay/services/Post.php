<?php
namespace fay\services;

use fay\core\ErrorException;
use fay\core\Loader;
use fay\core\Service;
use fay\core\Sql;
use fay\helpers\ArrayHelper;
use fay\helpers\FieldHelper;
use fay\helpers\StringHelper;
use fay\models\tables\Categories;
use fay\models\tables\Posts;
use fay\models\tables\PostsCategories;
use fay\models\tables\PostsFiles;
use fay\models\tables\PostsTags;
use fay\models\tables\PostPropInt;
use fay\models\tables\PostPropVarchar;
use fay\models\tables\PostPropText;
use fay\models\tables\PostLikes;
use fay\models\tables\PostMeta;
use fay\helpers\Request;
use fay\services\post\Extra;
use fay\services\post\Meta;
use fay\services\post\Prop;
use fay\models\tables\UserCounter;
use fay\services\post\Tag as PostTag;
use fay\core\Hook;
use fay\models\tables\PostFavorites;
use fay\models\tables\PostExtra;
use fay\services\post\Category as PostCategory;
use fay\services\post\File as PostFile;

/**
 * 文章服务
 */
class Post extends Service{
	/**
	 * 允许在接口调用时返回的字段
	 */
	public static $public_fields = array(
		'post'=>array(
			'id', 'title', 'content', 'content_type', 'publish_time', 'thumbnail', 'abstract',
		),
		'category'=>array(
			'id', 'title', 'alias',
		),
		'categories'=>array(
			'id', 'title', 'alias',
		),
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
		'nav'=>array(
			'id', 'title',
		),
		'tags'=>array(
			'id', 'title',
		),
		'files'=>array(
			'file_id', 'description', 'is_image',
		),
		'props'=>array(
			'*',//这里指定的是属性别名，取值视后台设定而定
		),
		'meta'=>array(
			'comments', 'views', 'likes', 'favorites',
		),
		'extra'=>array(
			'markdown', 'seo_title', 'seo_keywords', 'seo_description',
		),
	);
	
	/**
	 * 默认接口返回字段
	 */
	public static $default_fields = array(
		'post'=>array(
			'id', 'title', 'content', 'content_type', 'publish_time', 'thumbnail', 'abstract',
		),
		'category'=>array(
			'id', 'title', 'alias',
		),
		'user'=>array(
			'id', 'nickname', 'avatar',
		)
	);

	/**
	 * @param string $class_name
	 * @return Post
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
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
	 * @return int 文章ID
	 */
	public function create($post, $extra = array(), $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		
		$post['create_time'] = \F::app()->current_time;
		$post['last_modified_time'] = \F::app()->current_time;
		$post['user_id'] = $user_id;
		empty($post['publish_time']) && $post['publish_time'] = \F::app()->current_time;
		$post['publish_date'] = date('Y-m-d', $post['publish_time']);
		
		//过滤掉多余的数据
		$post_id = Posts::model()->insert($post, true);
		
		//Meta
		$post_meta = array(
			'post_id'=>$post_id,
		);
		if(isset($extra['meta'])){
			$post_meta = $post_meta + $extra['meta'];
		}
		
		PostMeta::model()->insert($post_meta);
		
		//扩展信息
		$post_extra = array(
			'post_id'=>$post_id,
			'ip_int'=>Request::ip2int(\F::app()->ip),
		);
		if(isset($extra['extra'])){
			$post_extra = $post_extra + $extra['extra'];
		}
		//特殊处理下text字段
		if(empty($post_extra['markdown'])){
			$post_extra['markdown'] = '';
		}
		
		PostExtra::model()->insert($post_extra);
		
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
		if(isset($extra['tags'])){
			PostTag::service()->set($extra['tags'], $post_id);
		}
		
		//附件
		if(isset($extra['files'])){
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
		if(isset($extra['props'])){
			$this->createPropertySet($post_id, $extra['props']);
		}
		
		if(isset($post['status'])){
			//如果有传入文章状态，且文章状态为“已发布”，用户文章数加一
			if($post['status'] == Posts::STATUS_PUBLISHED){
				//用户文章数加一
				UserCounter::model()->incr($user_id, 'posts', 1);
				
				//相关标签文章数加一
				PostTag::service()->incr($post_id);
			}
		}else{
			//如果未传入status，获取文章状态进行判断
			$post = Posts::model()->find($post_id, 'status');
			if($post['status'] == Posts::STATUS_PUBLISHED){
				//用户文章数加一
				UserCounter::model()->incr($user_id, 'posts', 1);
				
				//相关标签文章数加一
				PostTag::service()->incr($post_id);
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
	 * @param array $data posts表相关字段
	 * @param array $extra 其它字段
	 *   - categories 附加分类ID，逗号分隔或一维数组。若不传，则不会更新，若传了空数组，则清空附加分类。
	 *   - tags 标签文本，逗号分割或一维数组。若不传，则不会更新，若传了空数组，则清空标签。
	 *   - files 由文件ID为键，文件描述为值构成的关联数组。若不传，则不会更新，若传了空数组，则清空附件。
	 *   - props 以属性ID为键，属性值为值构成的关联数组。若不传，则不会更新，若传了空数组，则清空属性。
	 * @param bool $update_last_modified_time 是否更新“最后更新时间”。默认为true
	 * @return bool
	 */
	public function update($post_id, $data, $extra = array(), $update_last_modified_time = true){
		//获取原文章
		$old_post = Posts::model()->find($post_id, 'user_id,deleted,status');
		if(!$old_post){
			return false;
		}
		
		if($update_last_modified_time){
			$data['last_modified_time'] = \F::app()->current_time;
		}else if(isset($data['last_modified_time'])){
			unset($data['last_modified_time']);
		}
		
		//过滤掉多余的数据
		Posts::model()->update($data, $post_id, true);
		
		//计数表
		if(!empty($extra['meta'])){
			//排除不可编辑的字段
			PostMeta::model()->update($extra['meta'], $post_id, true);
		}
		
		//扩展表
		if(!empty($extra['extra'])){
			//排除不可编辑的字段
			PostExtra::model()->update($extra['extra'], $post_id, true);
		}
		
		//若原文章未删除，更新用户及标签的文章数
		if(!$old_post['deleted']){
			if($old_post['status'] == Posts::STATUS_PUBLISHED &&
				isset($data['status']) && $data['status'] != Posts::STATUS_PUBLISHED){
				//若原文章是“已发布”状态，且新状态不是“已发布”
				UserCounter::model()->incr($old_post['user_id'], 'posts', -1);
		
				//相关标签文章数减一
				PostTag::service()->decr($post_id);
			}else if($old_post['status'] != Posts::STATUS_PUBLISHED &&
				isset($data['status']) && $data['status'] == Posts::STATUS_PUBLISHED){
				//若原文章不是“已发布”状态，且新状态是“已发布”
				UserCounter::model()->incr($old_post['user_id'], 'posts', 1);
		
				//相关标签文章数加一
				PostTag::service()->incr($post_id);
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
			PostTag::service()->set($extra['tags'], $post_id);
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
			$props = Prop::service()->getProps($post_id);
		}
		Prop::service()->createPropertySet($post_id, $props, $data);
	}
	
	/**
	 * 更新一个文章属性集
	 * @param int $post_id 文章ID
	 * @param array $data 以属性ID为键的属性键值数组
	 * @param null|array $props 属性。若为null，则根据文章ID获取属性
	 */
	public function updatePropertySet($post_id, $data, $props = null){
		if($props === null){
			$props = Prop::service()->getProps($post_id);
		}
		Prop::service()->updatePropertySet($post_id, $props, $data);
	}
	
	/**
	 * 彻底删除一篇文章
	 * @param $post_id
	 * @return bool
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
			PostTag::service()->decr($post_id);
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
		
		//删除文章扩展信息
		PostExtra::model()->delete('post_id = ' . $post_id);
		
		return true;
	}
	
	/**
	 * 删除一篇文章
	 * @param int $post_id 文章ID
	 * @return bool
	 */
	public function delete($post_id){
		$post = Posts::model()->find($post_id, 'user_id,deleted,status');
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
			PostTag::service()->decr($post_id);
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
	 * @return bool
	 */
	public function undelete($post_id){
		$post = Posts::model()->find($post_id, 'user_id,deleted,status');
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
			PostTag::service()->incr($post_id);
		}
		
		//执行钩子
		Hook::getInstance()->call('after_post_undeleted', array(
			'post_id'=>$post_id,
		));
		
		return true;
	}
	
	/**
	 * 返回文章所属附加分类信息的二维数组
	 * @param int $id 文章ID
	 * @param string|array $fields categories表的字段
	 * @return array
	 */
	public function getCats($id, $fields = 'id,title,alias'){
		$sql = new Sql();
		return $sql->from(array('pc'=>'posts_categories'), '')
			->joinLeft(array('c'=>'categories'), 'pc.cat_id = c.id', $fields)
			->where(array('pc.post_id = ?'=>$id))
			->order('c.sort')
			->fetchAll();
	}
	
	/**
	 * 返回一篇文章信息
	 * @param int $id 文章ID
	 * @param string|array $fields 可指定返回字段
	 *  - post.*系列可指定posts表返回字段，若有一项为'post.*'，则返回所有字段
	 *  - meta.*系列可指定post_meta表返回字段，若有一项为'meta.*'，则返回所有字段
	 *  - tags.*系列可指定标签相关字段，可选tags表字段，若有一项为'tags.*'，则返回所有字段
	 *  - nav.*系列用于指定上一篇，下一篇返回的字段，可指定posts表返回字段，若有一项为'nav.*'，则返回除content字段外的所有字段
	 *  - files.*系列可指定posts_files表返回字段，若有一项为'posts_files.*'，则返回所有字段
	 *  - props.*系列可指定返回哪些文章分类属性，若有一项为'props.*'，则返回所有文章分类属性
	 *  - user.*系列可指定作者信息，格式参照\fay\services\User::get()
	 *  - categories.*系列可指定附加分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
	 *  - category.*系列可指定主分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
	 * @param int|string|array $cat 若指定分类（可以是id，alias或者包含left_value, right_value值的数组），
	 *    则只会在此分类及其子分类下搜索该篇文章<br>
	 *    该功能主要用于多栏目不同界面的时候，文章不要显示到其它栏目去
	 * @param bool $only_published 若为true，则只在已发布的文章里搜索。默认为true
	 * @return array
	 */
	public function get($id, $fields = 'post.*', $cat = null, $only_published = true){
		//解析$fields
		$fields = FieldHelper::parse($fields, 'post');
		if(empty($fields['post']) || in_array('*', $fields['post'])){
			//若未指定返回字段，初始化
			$fields['post'] = Posts::model()->getFields();
		}
		
		$post_fields = $fields['post'];
		if(!empty($fields['user']) && !in_array('user_id', $post_fields)){
			//如果要获取作者信息，则必须搜出user_id
			$post_fields[] = 'user_id';
		}
		if(!empty($fields['category']) && !in_array('cat_id', $post_fields)){
			//如果要获取分类信息，则必须搜出cat_id
			$post_fields[] = 'cat_id';
		}
		
		if(!empty($fields['nav'])){
			//如果要获取上一篇，下一篇，则必须搜出publish_time, sort, cat_id
			if(!in_array('publish_time', $post_fields)){
				$post_fields[] = 'publish_time';
			}
			if(!in_array('sort', $post_fields)){
				$post_fields[] = 'sort';
			}
			if(!in_array('cat_id', $post_fields)){
				$post_fields[] = 'cat_id';
			}
		}
		
		if(!empty($fields['props']) && !in_array('cat_id', $post_fields)){
			//如果要获取附加属性，必须搜出cat_id
			$post_fields[] = 'cat_id';
		}
		
		if(isset($fields['extra']) && in_array('seo_title', $fields['extra']) && !in_array('title', $post_fields)){
			//如果要获取seo_title，必须搜出title
			$post_fields[] = 'title';
		}
		if(isset($fields['extra']) && in_array('seo_keywords', $fields['extra']) && !in_array('title', $post_fields)){
			//如果要获取seo_title，必须搜出title
			$post_fields[] = 'title';
		}
		if(isset($fields['extra']) && in_array('seo_description', $fields['extra'])){
			//如果要获取seo_title，必须搜出title, content
			if(!in_array('abstract', $post_fields)){
				$post_fields[] = 'abstract';
			}
			if(!in_array('content', $post_fields)){
				$post_fields[] = 'content';
			}
		}
		
		$sql = new Sql();
		$sql->from(array('p'=>'posts'), $post_fields)
			->where(array(
				'p.id = ?'=>$id,
			));
		
		//仅搜索已发布的文章
		if($only_published){
			$sql->where(array(
				'p.deleted = 0',
				'p.status = '.Posts::STATUS_PUBLISHED,
				'p.publish_time < '.\F::app()->current_time,
			));
		}
		
		//若指定了分类，加上分类条件限制
		if($cat){
			if(!is_array($cat)){
				$cat = Category::service()->get($cat, 'left_value,right_value');
			}
			
			if(!$cat){
				//指定分类不存在
				return false;
			}
			$sql->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id')
				->where(array(
					'c.left_value >= '.$cat['left_value'],
					'c.right_value <= '.$cat['right_value'],
				));
		}
		
		$post = $sql->fetchRow();
		if(!$post){
			return false;
		}
		
		if(isset($post['thumbnail'])){
			//如果有缩略图，将缩略图转为图片URL
			$post['thumbnail_url'] = File::getUrl($post['thumbnail'], File::PIC_ORIGINAL, array(
				'spare'=>'avatar',
			));
		}
		
		$return = array(
			'post'=>$post,
		);
		
		//meta
		if(!empty($fields['meta'])){
			$return['meta'] = Meta::service()->get($id, $fields['meta']);
		}
		
		//扩展信息
		if(!empty($fields['extra'])){
			$return['extra'] = Extra::service()->get($id, $fields['extra']);
		}
		
		//设置一下SEO信息
		if(isset($fields['extra']) && in_array('seo_title', $fields['extra']) && empty($return['extra']['seo_title'])){
			$return['extra']['seo_title'] = $post['title'];
		}
		if(isset($fields['extra']) && in_array('seo_keywords', $fields['extra']) && empty($return['extra']['seo_keywords'])){
			$return['extra']['seo_keywords'] = str_replace(array(
				' ', '|', '，'
			), ',', $post['title']);
		}
		if(isset($fields['extra']) && in_array('seo_description', $fields['extra']) && empty($return['extra']['seo_description'])){
			$return['extra']['seo_description'] = $post['abstract'] ? $post['abstract'] : trim(mb_substr(str_replace(array("\r\n", "\r", "\n"), ' ', strip_tags($post['content'])), 0, 150));
		}
		
		//作者信息
		if(!empty($fields['user'])){
			$return['user'] = User::service()->get($post['user_id'], $fields['user'], isset($fields['_extra']['user']) ? $fields['_extra']['user'] : array());
		}
		
		//标签
		if(!empty($fields['tags'])){
			$return['tags'] = PostTag::service()->get($id, $fields['tags']);
		}
		
		//附件
		if(!empty($fields['files'])){
			$return['files'] = PostFile::service()->get($id, $fields['files']);
		}
		
		//附加属性
		if(!empty($fields['props'])){
			if(in_array('*', $fields['props'])){
				$props = null;
			}else{
				$props = Prop::service()->mget($fields['props']);
			}
			$return['props'] = Prop::service()->getPropertySet($id, $props);
		}
		
		//附加分类
		if(!empty($fields['categories'])){
			$return['categories'] = PostCategory::service()->get($id, $fields['categories']);
		}
		
		//主分类
		if(!empty($fields['category'])){
			$return['category'] = Category::service()->get($post['cat_id'], $fields['category']);
		}
		
		//前后一篇文章导航
		if(!empty($fields['nav'])){
			//上一篇
			$return['nav']['prev'] = $this->getPrevPost($id, $fields['nav']);
			
			//下一篇
			$return['nav']['next'] = $this->getNextPost($id, $fields['nav']);
		}
		
		//过滤掉那些未指定返回，但出于某些原因先搜出来的字段
		foreach(array('user_id', 'publish_time', 'sort', 'cat_id', 'title', 'abstract', 'content') as $f){
			if(!in_array($f, $fields['post']) && in_array($f, $post_fields)){
				unset($return['post'][$f]);
			}
		}
		
		return $return;
	}
	
	/**
	 * 根据分类信息获取对应文章
	 * @param int|string|array $cat 父节点ID或别名
	 *  - 若为数字，视为分类ID获取分类；
	 *  - 若为字符串，视为分类别名获取分类；
	 *  - 若为数组，至少需要包括id,left_value,right_value信息；
	 * @param int $limit 显示文章数若为0，则不限制
	 * @param string|array $field 字段
	 *  - post.*系列可指定posts表返回字段，若有一项为'post.*'，则返回所有字段
	 *  - meta.*系列可指定post_meta表返回字段，若有一项为'meta.*'，则返回所有字段
	 *  - tags.*系列可指定标签相关字段，可选tags表字段，若有一项为'tags.*'，则返回所有字段
	 *  - files.*系列可指定posts_files表返回字段，若有一项为'posts_files.*'，则返回所有字段
	 *  - props.*系列可指定返回哪些文章分类属性，若有一项为'props.*'，则返回所有文章分类属性
	 *  - user.*系列可指定作者信息，格式参照\fay\services\User::get()
	 *  - categories.*系列可指定附加分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
	 *  - category.*系列可指定主分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
	 * @param boolean $children 若该参数为true，则返回所有该分类及其子分类所对应的文章
	 * @param string $order 排序字段
	 * @param mixed $conditions 附加条件
	 * @return array
	 */
	public function getByCat($cat, $limit = 10, $field = 'id,title,publish_time,thumbnail', $children = false, $order = 'is_top DESC, sort, publish_time DESC', $conditions = null){
		if(is_array($cat)){
			//分类数组
			return $this->getByCatArray($cat, $limit, $field, $children, $order, $conditions);
		}else if(StringHelper::isInt($cat)){
			//分类ID
			return $this->getByCatId($cat, $limit, $field, $children, $order, $conditions);
		}else{
			//分类别名
			return $this->getByCatAlias($cat, $limit, $field, $children, $order, $conditions);
		}
	}
	
	/**
	 * 根据分类别名获取对应的文章
	 * @param string $alias 分类别名
	 * @param int $limit 显示文章数若为0，则不限制
	 * @param string|array $fields 字段
	 *  - post.*系列可指定posts表返回字段，若有一项为'post.*'，则返回所有字段
	 *  - meta.*系列可指定post_meta表返回字段，若有一项为'meta.*'，则返回所有字段
	 *  - tags.*系列可指定标签相关字段，可选tags表字段，若有一项为'tags.*'，则返回所有字段
	 *  - files.*系列可指定posts_files表返回字段，若有一项为'posts_files.*'，则返回所有字段
	 *  - props.*系列可指定返回哪些文章分类属性，若有一项为'props.*'，则返回所有文章分类属性
	 *  - user.*系列可指定作者信息，格式参照\fay\services\User::get()
	 *  - categories.*系列可指定附加分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
	 *  - category.*系列可指定主分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
	 * @param boolean $children 若该参数为true，则返回所有该分类及其子分类所对应的文章
	 * @param string $order 排序字段
	 * @param mixed $conditions 附加条件
	 * @return array
	 */
	public function getByCatAlias($alias, $limit = 10, $fields = 'id,title,publish_time,thumbnail', $children = false, $order = 'is_top DESC, sort, publish_time DESC', $conditions = null){
		$cat = Categories::model()->fetchRow(array(
			'alias = ?'=>$alias
		), 'id,left_value,right_value');
		
		if(!$cat){
			//指定分类不存在，直接返回空数组
			return array();
		}else{
			return $this->getByCatArray($cat, $limit, $fields, $children, $order, $conditions);
		}
	}
	
	/**
	 * 根据分类ID获取对应的文章
	 * @param string $cat_id 分类ID
	 * @param int $limit 显示文章数若为0，则不限制
	 * @param string|array $fields 字段
	 *  - post.*系列可指定posts表返回字段，若有一项为'post.*'，则返回所有字段
	 *  - meta.*系列可指定post_meta表返回字段，若有一项为'meta.*'，则返回所有字段
	 *  - tags.*系列可指定标签相关字段，可选tags表字段，若有一项为'tags.*'，则返回所有字段
	 *  - files.*系列可指定posts_files表返回字段，若有一项为'posts_files.*'，则返回所有字段
	 *  - props.*系列可指定返回哪些文章分类属性，若有一项为'props.*'，则返回所有文章分类属性
	 *  - user.*系列可指定作者信息，格式参照\fay\services\User::get()
	 *  - categories.*系列可指定附加分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
	 *  - category.*系列可指定主分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
	 * @param boolean $children 若该参数为true，则返回所有该分类及其子分类所对应的文章
	 * @param string $order 排序字段
	 * @param mixed $conditions 附加条件
	 * @return array
	 */
	public function getByCatId($cat_id, $limit = 10, $fields = 'id,title,publish_time,thumbnail', $children = false, $order = 'is_top DESC, sort, publish_time DESC', $conditions = null){
		$cat = Categories::model()->find($cat_id, 'id,left_value,right_value');
		if(!$cat){
			//指定分类不存在，直接返回空数组
			return array();
		}else{
			return $this->getByCatArray($cat, $limit, $fields, $children, $order, $conditions);
		}
	}
	
	
	/**
	 * 根据分类数组获取对应的文章
	 * @param array $cat 分类数组，至少需要包括id,left_value,right_value信息
	 * @param int $limit 显示文章数若为0，则不限制
	 * @param string|array $fields 可指定返回字段
	 *  - post.*系列可指定posts表返回字段，若有一项为'post.*'，则返回所有字段
	 *  - meta.*系列可指定post_meta表返回字段，若有一项为'meta.*'，则返回所有字段
	 *  - tags.*系列可指定标签相关字段，可选tags表字段，若有一项为'tags.*'，则返回所有字段
	 *  - files.*系列可指定posts_files表返回字段，若有一项为'posts_files.*'，则返回所有字段
	 *  - props.*系列可指定返回哪些文章分类属性，若有一项为'props.*'，则返回所有文章分类属性
	 *  - user.*系列可指定作者信息，格式参照\fay\services\User::get()
	 *  - categories.*系列可指定附加分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
	 *  - category.*系列可指定主分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
	 * @param boolean $children 若该参数为true，则返回所有该分类及其子分类所对应的文章
	 * @param string $order 排序字段
	 * @param mixed $conditions 附加条件
	 * @return array
	 */
	public function getByCatArray($cat, $limit = 10, $fields = 'id,title,publish_time,thumbnail', $children = false, $order = 'is_top DESC, sort, publish_time DESC', $conditions = null){
		//解析$fields
		$fields = FieldHelper::parse($fields, 'post');
		if(empty($fields['post']) || in_array('*', $fields['post'])){
			//若未指定返回字段，初始化（默认不返回content，因为列表页基本是不会显示文章详情的）
			$fields['post'] = Posts::model()->getFields(array('content'));
		}
		
		$post_fields = $fields['post'];
		if(!empty($fields['user']) && !in_array('user_id', $post_fields)){
			//如果要获取作者信息，则必须搜出user_id
			$post_fields[] = 'user_id';
		}
		if(!empty($fields['category']) && !in_array('cat_id', $post_fields)){
			//如果要获取作者信息，则必须搜出user_id
			$post_fields[] = 'cat_id';
		}
		if(!in_array('id', $fields['post'])){
			//id字段无论如何都要返回，因为后面要用到
			$post_fields[] = 'id';
		}
		
		$sql = new Sql();
		$sql->from(array('p'=>'posts'), $post_fields)
			->joinLeft(array('pc'=>'posts_categories'), 'p.id = pc.post_id')
			->joinLeft(array('pm'=>'post_meta'), 'p.id = pm.post_id', '')
			->where(array(
				'deleted = 0',
				'publish_time < '.\F::app()->current_time,
				'status = '.Posts::STATUS_PUBLISHED,
			))
			->order($order)
			->group('p.id');
		if($limit){
			$sql->limit($limit);
		}
		if($children){
			$all_cats = Categories::model()->fetchCol('id', array(
				'left_value >= '.$cat['left_value'],
				'right_value <= '.$cat['right_value'],
			));
			$sql->orWhere(array(
				'pc.cat_id IN ('.implode(',', $all_cats).')',
				'p.cat_id IN ('.implode(',', $all_cats).')'
			));
		}else{
			$sql->orWhere(array(
				"pc.cat_id = {$cat['id']}",
				"p.cat_id = {$cat['id']}"
			));
		}
		if(!empty($conditions)){
			$sql->where($conditions);
		}
		$posts = $sql->fetchAll();
		if(!$posts){
			return array();
		}
		
		$post_ids = ArrayHelper::column($posts, 'id');
		//meta
		if(!empty($fields['meta'])){
			$post_metas = Meta::service()->mget($post_ids, $fields['meta']);
		}
		//扩展信息
		if(!empty($fields['extra'])){
			$post_extras = Extra::service()->mget($post_ids, $fields['extra']);
		}
		
		//标签
		if(!empty($fields['tags'])){
			$post_tags = PostTag::service()->mget($post_ids, $fields['tags']);
		}
		
		//附件
		if(!empty($fields['files'])){
			$post_files = PostFile::service()->mget($post_ids, $fields['files']);
		}
		
		//附加分类
		if(!empty($fields['categories'])){
			$post_categories = PostCategory::service()->mget($post_ids, $fields['categories']);
		}
		
		//主分类
		if(!empty($fields['category'])){
			$cat_ids = ArrayHelper::column($posts, 'cat_id');
			$post_category = Category::service()->mget(array_unique($cat_ids), $fields['category']);
		}
		
		$return = array();
		foreach($posts as $p){
			if(isset($p['thumbnail'])){
				//如果有缩略图，将缩略图转为图片URL
				$p['thumbnail_url'] = File::getUrl($p['thumbnail'], File::PIC_ORIGINAL, array(
					'spare'=>'avatar',
				));
			}
			
			$post['post'] = $p;
			//meta
			if(isset($post_metas)){
				$post['meta'] = $post_metas[$p['id']];
			}
			//扩展信息
			if(isset($post_extras)){
				$post['extra'] = $post_extras[$p['id']];
			}
			
			//标签
			if(isset($post_tags)){
				$post['tags'] = $post_tags[$p['id']];
			}
			
			//附件
			if(isset($post_files)){
				$post['files'] = $post_files[$p['id']];
			}
			
			//附加分类
			if(isset($post_categories)){
				$post['categories'] = $post_categories[$p['id']];
			}
			
			//主分类
			if(isset($post_category)){
				$post['category'] = $post_category[$p['cat_id']];
			}
			
			//作者信息
			if(!empty($fields['user'])){
				$post['user'] = User::service()->get($p['user_id'], $fields['user']);
			}
			
			//附加属性
			if(!empty($fields['props'])){
				if(in_array('*', $fields['props'])){
					$props = null;
				}else{
					$props = Prop::service()->mget($fields['props']);
				}
				$post['props'] = Prop::service()->getPropertySet($p['id'], $props);
			}
			
			//过滤掉那些未指定返回，但出于某些原因先搜出来的字段
			foreach(array('id', 'user_id', 'cat_id') as $f){
				if(!in_array($f, $fields['post']) && in_array($f, $post_fields)){
					unset($post['post'][$f]);
				}
			}
			
			$return[] = $post;
		}
		
		return $return;
	}
	
	/**
	 * 用于获取文章链接
	 * 出于效率考虑，不对本函数做任何配置项，必要的时候，直接重写此函数
	 * @param $post
	 * @param string $controller
	 * @return string
	 * @internal param array|int $post文章ID或者包含文章信息的数组
	 */
	public function getLink($post, $controller = 'post'){
		if(is_array($post)){
			$post_id = $post['id'];
		}else{
			$post_id = $post;
		}
		return \F::app()->view->url($controller . '/' . $post_id);
	}
	
	/**
	 * 获取当前文章上一篇文章
	 * （此处上一篇是比当前文章新一点的那篇）
	 * @param int $post_id 文章ID
	 * @param string|array $fields 文章字段（posts表字段）
	 * @return array|bool
	 */
	public function getPrevPost($post_id, $fields = 'id,title'){
		$sql = new Sql();
		//根据文章ID获取当前文章
		$post = Posts::model()->find($post_id, 'id,cat_id,publish_time,sort');
		if(!is_array($fields)){
			$fields = explode(',', $fields);
		}
		$post_fields = $fields;
		if(!in_array('sort', $post_fields)){
			$post_fields[] = 'sort';
		}
		if(!in_array('publish_time', $post_fields)){
			$post_fields[] = 'publish_time';
		}
		$prev_post = $sql->from(array('p'=>'posts'), $post_fields)
			->where(array(
				'p.cat_id = '.$post['cat_id'],
				'p.deleted = 0',
				'p.status = '.Posts::STATUS_PUBLISHED,
				'p.publish_time < '.\F::app()->current_time,
				"p.publish_time >= {$post['publish_time']}",
				"p.sort <= {$post['sort']}",
				"p.id != {$post['id']}",
			))
			->order('is_top, sort DESC, publish_time')
			->fetchRow();
		if($prev_post){
			if($prev_post['publish_time'] == $post['publish_time'] && $prev_post['sort'] == $post['sort']){
				//当排序值和发布时间都一样的情况下，可能出错，需要重新根据ID搜索（不太可能发布时间都一样的）
				$prev_post = $sql->from(array('p'=>'posts'), 'id,title,sort,publish_time')
					->where(array(
						'p.cat_id = '.$post['cat_id'],
						'p.deleted = 0',
						'p.status = '.Posts::STATUS_PUBLISHED,
						'p.publish_time < '.\F::app()->current_time,
						"p.publish_time = {$post['publish_time']}",
						"p.sort = {$post['sort']}",
						"p.id > {$post['id']}",
					))
					->order('id ASC')
					->fetchRow();
			}
			if(!in_array('sort', $fields)){
				unset($prev_post['sort']);
			}
			if(!in_array('publish_time', $fields)){
				unset($prev_post['publish_time']);
			}
		}
		return $prev_post;
	}
	
	/**
	 * 获取当前文章下一篇文章
	 * （此处下一篇是比当前文章老一点的那篇）
	 * @param int $post_id 文章ID
	 * @param string|array $fields 文章字段（posts表字段）
	 * @return array|bool
	 */
	public function getNextPost($post_id, $fields = 'id,title'){
		$sql = new Sql();
		//根据文章ID获取当前文章
		$post = Posts::model()->find($post_id, 'id,cat_id,publish_time,sort');
		if(!is_array($fields)){
			$fields = explode(',', $fields);
		}
		$post_fields = $fields;
		if(!in_array('sort', $post_fields)){
			$post_fields[] = 'sort';
		}
		if(!in_array('publish_time', $post_fields)){
			$post_fields[] = 'publish_time';
		}
		$next_post = $sql->from(array('p'=>'posts'), $post_fields)
			->where(array(
				'p.cat_id = '.$post['cat_id'],
				'p.deleted = 0',
				'p.status = '.Posts::STATUS_PUBLISHED,
				'p.publish_time < '.\F::app()->current_time,
				"p.publish_time <= {$post['publish_time']}",
				"p.sort >= {$post['sort']}",
				"p.id != {$post['id']}",
			))
			->order('is_top, sort DESC, publish_time')
			->fetchRow();
		if($next_post){
			if($next_post['publish_time'] == $post['publish_time'] && $next_post['sort'] == $post['sort']){
				//当排序值和发布时间都一样的情况下，可能出错，需要重新根据ID搜索（不太可能发布时间都一样的）
				$next_post = $sql->from(array('p'=>'posts'), 'id,title,sort,publish_time')
					->where(array(
						'p.cat_id = '.$post['cat_id'],
						'p.deleted = 0',
						'p.status = '.Posts::STATUS_PUBLISHED,
						'p.publish_time < '.\F::app()->current_time,
						"p.publish_time = {$post['publish_time']}",
						"p.sort = {$post['sort']}",
						"p.id < {$post['id']}",
					))
					->order('id ASC')
					->fetchRow();
			}
			if(!in_array('sort', $fields)){
				unset($next_post['sort']);
			}
			if(!in_array('publish_time', $fields)){
				unset($next_post['publish_time']);
			}
		}
		return $next_post;
	}
	
	/**
	 * 根据文章属性、分类，获取对应的文章（仅支持下拉，多选属性，不支持文本属性）<br>
	 * 分类包含所有子分类
	 * @param $prop
	 * @param string $prop_value 属性值
	 * @param int $limit 返回文章数
	 * @param int $cat_id
	 * @param string|array $fields 返回posts表中的字段（cat_title）默认返回
	 * @param string $order 排序字段
	 * @return array
	 * @internal param int|string $prop_alias 可传入属性ID或者alias
	 */
	public function getByProp($prop, $prop_value, $limit = 10, $cat_id = 0, $fields = 'id,title,thumbnail,abstract', $order = 'p.is_top DESC, p.sort, p.publish_time DESC'){
		if(!StringHelper::isInt($prop)){
			$prop = Prop::service()->getIdByAlias($prop);
		}
		$sql = new Sql();
		$sql->from(array('p'=>'posts'), $fields)
			->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title')
			->where(array(
				'p.deleted = 0',
				'p.status = '.Posts::STATUS_PUBLISHED,
				'p.publish_time < '.\F::app()->current_time,
				'pi.content = '.$prop_value,
			))
			->joinLeft(array('pi'=>'post_prop_int'), array(
				'pi.prop_id = '.$prop,
				'pi.post_id = p.id',
			))
			->order($order)
			->group('p.id')
			->limit($limit)
		;
		if(!empty($cat_id)){
			$cat = Category::service()->get($cat_id);
			$sql->where(array(
				'c.left_value >= '.$cat['left_value'],
				'c.right_value <= '.$cat['right_value'],
			));
		}
		return $sql->fetchAll();
	}
	
	/**
	 * 格式化文章内容（若是markdown语法，会转换为html，若是纯文本，会把回车转为p标签）
	 * @param array $post 至少包含content和content_type的数组
	 * @return string
	 */
	public static function formatContent($post){
		if($post['content_type'] == Posts::CONTENT_TYPE_MARKDOWN){
			Loader::vendor('Markdown/markdown');
			return Markdown($post['content']);
		}else if($post['content_type'] == Posts::CONTENT_TYPE_TEXTAREA){
			return StringHelper::nl2p($post['content']);
		}else{
			return $post['content'];
		}
	}
	
	/**
	 * 判断当前登录用户是否对该文章有编辑权限
	 * @param int $post 文章
	 *  - 若是数组，视为文章表行记录，必须包含user_id, status和cat_id字段
	 *  - 若是数字，视为文章ID，会根据ID搜索数据库
	 * @param int|null $new_status 更新后的状态，不传则不做验证
	 * @param int|null $new_cat_id 更新后的分类，不传则不做验证
	 * @param int|null $user_id 用户ID，若为空，则默认为当前登录用户
	 * @return bool
	 * @throws ErrorException
	 */
	public static function checkEditPermission($post, $new_status = null, $new_cat_id = null, $user_id = null){
		if(!is_array($post)){
			$post = Posts::model()->find($post, 'user_id,cat_id,status');
		}
		$user_id || $user_id = \F::app()->current_user;
		
		if(empty($post['user_id'])){
			throw new ErrorException('指定文章不存在');
		}
		
		if($post['user_id'] == $user_id){
			//自己的文章总是有权限还原的
			return true;
		}
		
		if(User::service()->isAdmin($user_id) &&
			User::service()->checkPermission('admin/post/edit', $user_id) &&
			PostCategory::service()->isAllowedCat($post['cat_id'], $user_id)){
			//是管理员，有还原权限，且有当前文章的分类权限
			
			if($new_cat_id && !PostCategory::service()->isAllowedCat($new_cat_id, $user_id)){
				//若指定了新分类，判断用户是否对新分类有编辑权限
				return false;
			}
			
			//文章状态被编辑
			if($new_status){
				//若系统开启文章审核功能；且文章原状态不是“通过审核”，被修改为“通过审核”；且该用户无审核权限，返回false
				if(Option::get('system:post_review') &&
					$post['status'] != Posts::STATUS_REVIEWED && $new_status == Posts::STATUS_REVIEWED &&
					!\F::app()->checkPermission('admin/post/review')
				){
					return false;
				}
				//若系统开启文章审核功能；文章原状态不是“已发布”，被修改为“已发布”；且该用户无发布权限，返回false
				if(Option::get('system:post_review') &&
					$post['status'] != Posts::STATUS_PUBLISHED && $new_status == Posts::STATUS_PUBLISHED &&
					!\F::app()->checkPermission('admin/post/publish')
				){
					return false;
				}
			}
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * 判断当前登录用户是否对该文章有删除权限
	 * @param int $post 文章
	 *  - 若是数组，视为文章表行记录，必须包含user_id和cat_id字段
	 *  - 若是数字，视为文章ID，会根据ID搜索数据库
	 * @param int $user_id 用户ID，若为空，则默认为当前登录用户
	 * @return bool
	 * @throws ErrorException
	 */
	public static function checkDeletePermission($post, $user_id = null){
		if(!is_array($post)){
			$post = Posts::model()->find($post, 'user_id,cat_id');
		}
		$user_id || $user_id = \F::app()->current_user;
		
		if(empty($post['user_id'])){
			throw new ErrorException('指定文章不存在');
		}
		
		if($post['user_id'] == $user_id){
			//自己的文章总是有权限删除的
			return true;
		}
		
		if(User::service()->isAdmin($user_id) &&
			User::service()->checkPermission('admin/post/delete', $user_id) &&
			PostCategory::service()->isAllowedCat($post['cat_id'], $user_id)){
			//是管理员，有删除权限，且有当前文章的分类权限
			return true;
		}
		
		return false;
	}
	
	/**
	 * 判断当前登录用户是否对该文章有还原权限
	 * @param int $post 文章
	 *  - 若是数组，视为文章表行记录，必须包含user_id和cat_id字段
	 *  - 若是数字，视为文章ID，会根据ID搜索数据库
	 * @param int $user_id 用户ID，若为空，则默认为当前登录用户
	 * @return bool
	 * @throws ErrorException
	 */
	public static function checkUndeletePermission($post, $user_id = null){
		if(!is_array($post)){
			$post = Posts::model()->find($post, 'user_id,cat_id');
		}
		$user_id || $user_id = \F::app()->current_user;
		
		if(empty($post['user_id'])){
			throw new ErrorException('指定文章不存在');
		}
		
		if($post['user_id'] == $user_id){
			//自己的文章总是有权限还原的
			return true;
		}
		
		if(User::service()->isAdmin($user_id) &&
			User::service()->checkPermission('admin/post/undelete', $user_id) &&
			PostCategory::service()->isAllowedCat($post['cat_id'], $user_id)){
			//是管理员，有还原权限，且有当前文章的分类权限
			return true;
		}
		
		return false;
	}
	
	/**
	 * 判断当前登录用户是否对该文章有永久删除权限
	 * @param int $post 文章
	 *  - 若是数组，视为文章表行记录，必须包含user_id和cat_id字段
	 *  - 若是数字，视为文章ID，会根据ID搜索数据库
	 * @param int $user_id 用户ID，若为空，则默认为当前登录用户
	 * @return bool
	 * @throws ErrorException
	 */
	public static function checkRemovePermission($post, $user_id = null){
		if(!is_array($post)){
			$post = Posts::model()->find($post, 'user_id,cat_id');
		}
		$user_id || $user_id = \F::app()->current_user;
		
		if(empty($post['user_id'])){
			throw new ErrorException('指定文章不存在');
		}
		
		if($post['user_id'] == $user_id){
			//自己的文章总是有权限永久删除的
			return true;
		}
		
		if(User::service()->isAdmin($user_id) &&
			User::service()->checkPermission('admin/post/remove', $user_id) &&
			PostCategory::service()->isAllowedCat($post['cat_id'], $user_id)){
			//是管理员，有永久删除权限，且有当前文章的分类权限
			return true;
		}
		
		return false;
	}
	
	/**
	 * 判断一个文章ID是否存在（“已删除/未发布/未到定时发布时间”的文章都被视为不存在）
	 * @param int $post_id
	 * @return bool 若文章已发布且未删除返回true，否则返回false
	 */
	public static function isPostIdExist($post_id){
		if($post_id){
			$post = Posts::model()->find($post_id, 'deleted,publish_time,status');
			if($post['deleted'] || $post['publish_time'] > \F::app()->current_time || $post['status'] != Posts::STATUS_PUBLISHED){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 批量获取文章信息
	 * @param array $post_ids 文章ID构成的一维数组
	 * @param string|array $fields 返回字段
	 *  - post.*系列可指定posts表返回字段，若有一项为'post.*'，则返回所有字段
	 *  - meta.*系列可指定post_meta表返回字段，若有一项为'meta.*'，则返回所有字段
	 *  - tags.*系列可指定标签相关字段，可选tags表字段，若有一项为'tags.*'，则返回所有字段
	 *  - nav.*系列用于指定上一篇，下一篇返回的字段，可指定posts表返回字段，若有一项为'nav.*'，则返回除content字段外的所有字段
	 *  - files.*系列可指定posts_files表返回字段，若有一项为'posts_files.*'，则返回所有字段
	 *  - props.*系列可指定返回哪些文章分类属性，若有一项为'props.*'，则返回所有文章分类属性
	 *  - user.*系列可指定作者信息，格式参照\fay\services\User::get()
	 *  - categories.*系列可指定附加分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
	 *  - category.*系列可指定主分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
	 * @param bool $only_published 若为true，则只在已发布的文章里搜索。默认为true
	 * @return array
	 */
	public function mget($post_ids, $fields, $only_published = true){
		if(!$post_ids){
			return array();
		}
		//解析$fields
		$fields = FieldHelper::parse($fields, 'post');
		if(empty($fields['post']) || in_array('*', $fields['post'])){
			//若未指定返回字段，初始化（默认不返回content，因为列表页基本是不会显示文章详情的）
			$fields['post'] = Posts::model()->getFields(array('content'));
		}
		
		$post_fields = $fields['post'];
		if(!empty($fields['user']) && !in_array('user_id', $post_fields)){
			//如果要获取作者信息，则必须搜出user_id
			$post_fields[] = 'user_id';
		}
		if(!empty($fields['category']) && !in_array('cat_id', $post_fields)){
			//如果要获取分类信息，则必须搜出cat_id
			$post_fields[] = 'cat_id';
		}
		if(!in_array('id', $fields['post'])){
			//id字段无论如何都要返回，因为后面要用到
			$post_fields[] = 'id';
		}
		
		$sql = new Sql();
		$sql->from(array('p'=>Posts::model()->getTableName()), $post_fields)
			->where('id IN (?)', $post_ids);
		
		//仅搜索已发布的文章
		if($only_published){
			$sql->where(array(
				'p.deleted = 0',
				'p.status = '.Posts::STATUS_PUBLISHED,
				'p.publish_time < '.\F::app()->current_time,
			));
		}
		
		$posts = $sql->fetchAll();
		
		if(!$posts){
			return array();
		}
		
		//meta
		if(!empty($fields['meta'])){
			$post_metas = Meta::service()->mget($post_ids, $fields['meta']);
		}
		
		//扩展信息
		if(!empty($fields['extra'])){
			$post_extras = Meta::service()->mget($post_ids, $fields['extra']);
		}
		
		//标签
		if(!empty($fields['tags'])){
			$post_tags = PostTag::service()->mget($post_ids, $fields['tags']);
		}
		
		//附件
		if(!empty($fields['files'])){
			$post_files = PostFile::service()->mget($post_ids, $fields['files']);
		}
		
		//附加分类
		if(!empty($fields['categories'])){
			$post_categories = PostCategory::service()->mget($post_ids, $fields['categories']);
		}
		
		//主分类
		if(!empty($fields['category'])){
			$cat_ids = ArrayHelper::column($posts, 'cat_id');
			$post_category = Category::service()->mget(array_unique($cat_ids), $fields['category']);
		}
		
		$return = array();
		//以传入文章ID顺序返回文章结构
		foreach($post_ids as $pid){
			$p = null;
			foreach($posts as $k => $pi){
				//从$posts中获取当前文章ID
				if($pid == $pi['id']){
					$p = $pi;
					unset($posts[$k]);
					break;
				}
			}
			if(!$p){
				//文章不存在（一般不会发生）
				continue;
			}
			
			if(isset($p['thumbnail'])){
				//如果有缩略图，将缩略图转为图片URL
				$p['thumbnail_url'] = File::getUrl($p['thumbnail'], File::PIC_ORIGINAL, array(
					'spare'=>'avatar',
				));
			}
			
			$post['post'] = $p;
			
			//meta
			if(isset($post_metas)){
				$post['meta'] = $post_metas[$p['id']];
			}
			
			//扩展信息
			if(isset($post_extras)){
				$post['extra'] = $post_extras[$p['id']];
			}
			
			//标签
			if(isset($post_tags)){
				$post['tags'] = $post_tags[$p['id']];
			}
			
			//附件
			if(isset($post_files)){
				$post['files'] = $post_files[$p['id']];
			}
			
			//附加分类
			if(isset($post_categories)){
				$post['categories'] = $post_categories[$p['id']];
			}
			
			//主分类
			if(isset($post_category)){
				$post['category'] = $post_category[$p['cat_id']];
			}
			
			//作者信息
			if(!empty($fields['user'])){
				$post['user'] = User::service()->get($p['user_id'], $fields['user']);
			}
			
			//附加属性
			if(!empty($fields['props'])){
				if(in_array('*', $fields['props'])){
					$props = null;
				}else{
					$props = Prop::service()->mget($fields['props']);
				}
				$post['props'] = Prop::service()->getPropertySet($p['id'], $props);
			}
			
			//过滤掉那些未指定返回，但出于某些原因先搜出来的字段
			foreach(array('id', 'user_id', 'cat_id') as $f){
				if(!in_array($f, $fields['post']) && in_array($f, $post_fields)){
					unset($post['post'][$f]);
				}
			}
			
			$return[] = $post;
		}
		
		return $return;
	}
}