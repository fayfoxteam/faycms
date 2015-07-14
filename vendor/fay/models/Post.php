<?php
namespace fay\models;

use fay\core\Model;
use fay\core\Sql;
use fay\models\tables\Posts;
use fay\models\tables\PostsCategories;
use fay\models\tables\Messages;
use fay\models\tables\PostsFiles;
use fay\models\tables\Categories;
use fay\models\tables\Props;
use fay\models\tables\Favourites;
use fay\models\tables\Likes;
use fay\models\tables\PostsTags;
use fay\models\tables\PostPropInt;
use fay\models\tables\PostPropVarchar;
use fay\models\tables\PostPropText;
use fay\helpers\String;
use fay\core\Loader;
use fay\models\tables\Users;

class Post extends Model{
	
	/**
	 * @return Post
	 */
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 返回文章所属附加分类信息的二维数组
	 * @param int $id 文章ID
	 * @param int $fields categories表的字段
	 */
	public function getCats($id, $fields = 'id,title,alias'){
		$sql = new Sql();
		return $sql->from('posts_categories', 'pc', '')
			->joinLeft('categories', 'c', 'pc.cat_id = c.id', $fields)
			->where("pc.post_id = {$id}")
			->order('c.sort')
			->fetchAll();
	}
	
	/**
	 * 返回包含文章所属附加分类ID号的一维数组
	 * @param int $id 文章ID
	 */
	public function getCatIds($id){
		return PostsCategories::model()->fetchCol('cat_id', "post_id = {$id}");
	}
	
	public function getCount($status = null){
		$conditions = array('deleted = 0');
		if($status !== null){
			$conditions['status = ?'] = $status;
		}
		$result = Posts::model()->fetchRow($conditions, 'COUNT(*)');
		return $result['COUNT(*)'];
	}
	
	public function getDeletedCount(){
		$result = Posts::model()->fetchRow('deleted = 1', 'COUNT(*)');
		return $result['COUNT(*)'];
	}
	
	/**
	 * 返回一篇文章信息（返回字段已做去转义处理）
	 * @param int $id
	 * @param string $fields tags,messages,nav,files,props,user,categories
	 * @param int|string|array $cat 若指定分类（可以是id，alias或者包含left_value, right_value值的数组），
	 * 	则只会在此分类及其子分类下搜索该篇文章<br>
	 * 	该功能主要用于多栏目不同界面的时候，文章不要显示到其它栏目去
	 * @param null|bool $publish 若为true，则只在已发布的文章里搜索
	 */
	public function get($id, $fields = 'tags,messages,nav,files,props,user,categories', $cat = null, $publish = true){
		$sql = new Sql();
		
		$fields = explode(',', $fields);
		$sql->from('posts', 'p')
			->joinLeft('categories', 'c', 'p.cat_id = c.id', 'title AS cat_title')
			->where(array(
				'p.id = ?'=>$id,
			));
		
		//仅搜索已发布的文章
		if($publish === true){
			$sql->where(array(
				'p.deleted = 0',
				'p.publish_time < '.\F::app()->current_time,
				'p.status = '.Posts::STATUS_PUBLISHED,
			));
		}
		
		if(in_array('user', $fields)){
			$sql->joinLeft('users', 'u', 'p.user_id = u.id', 'username,nickname,realname,avatar');
		}
		
		if($cat != null){
			if(is_array($cat)){
				//无操作
			}else if(is_numeric($cat)){
				$cat = Category::model()->get($cat);
			}else{
				$cat = Category::model()->getByAlias($cat);
			}
			
			if(!$cat){
				//指定分类不存在，一般来说是Controller调用错误
				return false;
			}
			$sql->where(array(
				'c.left_value >= '.$cat['left_value'],
				'c.right_value <= '.$cat['right_value'],
			));
		}
		
		$post = $sql->fetchRow();
		if(!$post){
			return false;
		}

		//设置一下SEO信息
		$post['seo_title'] || $post['seo_title'] = $post['title'];
		$post['seo_keywords'] || $post['seo_keywords'] = str_replace(array(
			' ', '|', '，'
		), ',', $post['title']);
		$post['seo_description'] || $post['seo_description'] = $post['abstract'] ?
			$post['abstract'] : trim(mb_substr(strip_tags($post['content']), 0, 150));
		
		
		//tags
		if(in_array('tags', $fields)){
			$post['tags'] = $this->getTags($id);
		}
		
		//扩展分类
		if(in_array('categories', $fields)){
			$post['ext_cats'] = $sql->from('posts_categories', 'pc', '')
				->joinLeft('categories', 'c', 'pc.cat_id = c.id', 'title,id')
				->where('pc.post_id = '.$post['id'])
				->fetchAll();
		}
		
		//messages
		if(in_array('messages', $fields)){
			$post['messages'] = Message::model()->getAll($id, Messages::TYPE_POST_COMMENT);
		}
		
		//nav
		if(in_array('nav', $fields)){
			//previous post
			//此处上一篇是比当前文章新一点的那篇
			$prev_post = $sql->from('posts', 'p', 'id,title,sort,publish_time')
				->where(array(
					'p.deleted = 0',
					'p.publish_time < '.\F::app()->current_time,
					'p.status = '.Posts::STATUS_PUBLISHED,
					'p.cat_id = '.$post['cat_id'],
					"p.publish_time >= {$post['publish_time']}",
					"p.sort <= {$post['sort']}",
					"p.id != {$post['id']}",
				))
				->order('is_top, sort DESC, publish_time')
				->fetchRow();
			if($prev_post['publish_time'] == $post['publish_time'] && $prev_post['sort'] == $post['sort']){
				//当排序值和发布时间都一样的情况下，可能出错，需要重新根据ID搜索（不太可能发布时间都一样的）
				$prev_post = $sql->from('posts', 'p', 'id,title,sort,publish_time')
					->where(array(
						'p.deleted = 0',
						'p.publish_time < '.\F::app()->current_time,
						'p.status = '.Posts::STATUS_PUBLISHED,
						'p.cat_id = '.$post['cat_id'],
						"p.publish_time = {$post['publish_time']}",
						"p.sort = {$post['sort']}",
						"p.id > {$post['id']}",
					))
					->order('id ASC')
					->fetchRow();
			}
			$post['nav']['prev'] = $prev_post;
				
			//next post
			$next_post = $sql->from('posts', 'p', 'id,title,sort,publish_time')
				->where(array(
					'p.deleted = 0',
					'p.publish_time < '.\F::app()->current_time,
					'p.status = '.Posts::STATUS_PUBLISHED,
					'p.cat_id = '.$post['cat_id'],
					"p.publish_time <= {$post['publish_time']}",
					"p.sort >= {$post['sort']}",
					"p.id != {$post['id']}",
				))
				->order('is_top DESC, sort, publish_time DESC')
				->fetchRow();
			if($next_post['publish_time'] == $post['publish_time'] && $next_post['sort'] == $post['sort']){
				$next_post = $sql->from('posts', 'p', 'id,title,sort,publish_time')
					->where(array(
						'p.deleted = 0',
						'p.publish_time < '.\F::app()->current_time,
						'p.status = '.Posts::STATUS_PUBLISHED,
						'p.cat_id = '.$post['cat_id'],
						"p.publish_time = {$post['publish_time']}",
						"p.sort = {$post['sort']}",
						"p.id < {$post['id']}",
					))
					->order('id DESC')
					->fetchRow();
			}
			$post['nav']['next'] = $next_post;
		}
		
		//files
		if(in_array('files', $fields)){
			$post['files'] = PostsFiles::model()->fetchAll(array(
				'post_id = ?'=>$id,
			), 'file_id,description,is_image', 'sort');
		}
		
		if(in_array('props', $fields)){
			//文章所属分类
			$post_cat = Category::model()->get($post['cat_id'], 'title,left_value,right_value');
			//所有父级分类
			$post_cat_parents = Categories::model()->fetchCol('id', array(
				'left_value <= '.$post_cat['left_value'],
				'right_value >= '.$post_cat['right_value'],
			));
			//所有属性
			$props = Prop::model()->getAll($post_cat_parents, Props::TYPE_POST_CAT, '');
			
			$post['props'] = $this->getProps($id, $props);
		}
		return $post;
	}
	
	/**
	 * 获取文章附加属性<br>
	 * 可传入props（并不一定真的是当前文章分类对应的属性，比如编辑文章所属分类的时候会传入其他属性）<br>
	 * 若不传入，则会自动获取当前文章所属分类的属性集
	 */
	public function getProps($post_id, $props = array()){
		if(!$props){
			$post = Posts::model()->find($post_id, 'cat_id');
			$props = Prop::model()->getAll($post['cat_id'], Props::TYPE_POST_CAT);
		}
		
		return Prop::model()->getPropertySet('post_id', $post_id, $props, array(
			'varchar'=>'fay\models\tables\PostPropVarchar',
			'int'=>'fay\models\tables\PostPropInt',
			'text'=>'fay\models\tables\PostPropText',
		));
	}
	
	/**
	 * 根据分类信息获取对应文章<br>
	 * @param int|string|array $cat 父节点ID或别名
	 *  - 若为数字，视为分类ID获取分类；
	 *  - 若为字符串，视为分类别名获取分类；
	 *  - 若为数组，至少需要包括id,left_value,right_value信息；
	 * @param number $limit 显示文章数若为0，则不限制
	 * @param string $field 字段
	 * @param boolean $children 若该参数为true，则返回所有该分类及其子分类所对应的文章
	 * @param string $order 排序字段
	 * @param mixed $conditions 附加条件
	 */
	public function getByCat($cat, $limit = 10, $field = '!content', $children = false, $order = 'is_top DESC, sort, publish_time DESC', $conditions = null){
		if(is_array($cat)){
			//分类数组
			return $this->getByCatArray($cat, $limit, $field, $children, $order, $conditions);
		}else if(is_numeric($cat)){
			//分类ID
			return $this->getByCatId($cat, $limit, $field, $children, $order, $conditions);
		}else{
			//分类别名
			return $this->getByCatAlias($cat, $limit, $field, $children, $order, $conditions);
		}
	}
	
	/**
	 * 根据分类别名获取对应的文章<br>
	 * @param string $alias 分类别名
	 * @param number $limit 显示文章数若为0，则不限制
	 * @param string $field 字段
	 * @param boolean $children 若该参数为true，则返回所有该分类及其子分类所对应的文章
	 * @param string $order 排序字段
	 * @param mixed $conditions 附加条件
	 */
	public function getByCatAlias($alias, $limit = 10, $fields = '!content', $children = false, $order = 'is_top DESC, sort, publish_time DESC', $conditions = null){
		$cat = Categories::model()->fetchRow(array(
			'alias = ?'=>$alias
		), 'id,left_value,right_value');
		
		return $this->getByCatArray($cat, $limit, $fields, $children, $order, $conditions);
	}
	
	/**
	 * 根据分类ID获取对应的文章<br>
	 * @param string $cat_id 分类ID
	 * @param number $limit 显示文章数若为0，则不限制
	 * @param string $field 字段
	 * @param boolean $children 若该参数为true，则返回所有该分类及其子分类所对应的文章
	 * @param string $order 排序字段
	 * @param mixed $conditions 附加条件
	 */
	public function getByCatId($cat_id, $limit = 10, $fields = '!content', $children = false, $order = 'is_top DESC, sort, publish_time DESC', $conditions = null){
		$cat = Categories::model()->find($cat_id, 'id,left_value,right_value');
		return $this->getByCatArray($cat, $limit, $fields, $children, $order, $conditions);
	}


	/**
	 * 根据分类数组获取对应的文章<br>
	 * @param array $cat 分类数组，至少需要包括id,left_value,right_value信息
	 * @param number $limit 显示文章数若为0，则不限制
	 * @param string $field 字段
	 * @param boolean $children 若该参数为true，则返回所有该分类及其子分类所对应的文章
	 * @param string $order 排序字段
	 * @param mixed $conditions 附加条件
	 */
	public function getByCatArray($cat, $limit = 10, $field = '!content', $children = false, $order = 'is_top DESC, sort, publish_time DESC', $conditions = null){
		$sql = new Sql();
		$sql->from('posts', 'p', $field)
			->joinLeft('posts_categories', 'pc', 'p.id = pc.post_id')
			->where(array(
				'deleted = 0',
				'publish_time < '.\F::app()->current_time,
				'status = '.Posts::STATUS_PUBLISHED,
			))
			->order($order)
			->distinct(true);
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
		return $sql->fetchAll();
	}
	
	/**
	 * 用于获取文章链接
	 * 出于效率考虑，不对本函数做任何配置项，必要的时候，直接重写此函数
	 * @param int|array $post文章ID或者包含文章信息的数组
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
	 * 刷新文章评论数
	 * @param int $target
	 * @return int
	 */
	public function refreshComments($target){
		$comment_count = Messages::model()->fetchRow(array(
			'target = ?'=>$target,
			'type = '.Messages::TYPE_POST_COMMENT,
			'status = '.Messages::STATUS_APPROVED,
			'deleted = 0',
		), 'COUNT(*) AS count');
		
		Posts::model()->update(array(
			'comments'=>$comment_count['count'],
		), $target);
		return $comment_count['count'];
	}
	
	/**
	 * 是否已点赞
	 * @param int $post_id
	 * @param null|int $user_id
	 * @return boolean
	 */
	public function isLiked($post_id, $user_id = null){
		if($user_id === null){
			if(\F::app()->current_user){
				$user_id = \F::app()->current_user;
			}else{
				return false;
			}
		}
		
		if(Likes::model()->find(array($user_id, $post_id))){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 刷新posts表likes字段
	 * @param int $post_id
	 * @return int
	 */
	public function refreshLikes($post_id){
		$count = Likes::model()->fetchRow(array(
			'post_id = ?'=>$post_id
		), 'COUNT(*) AS count');
		
		Posts::model()->update(array(
			'likes'=>$count['count'],
		), $post_id);
		return $count['count'];
	}
	
	/**
	 * 点赞数+1（若需要点赞数作假的话，用此函数）
	 * @param int $post_id
	 */
	public function incLikes($post_id){
		Posts::model()->inc($post_id, 'likes', 1);
	}
	
	/**
	 * 点赞数-1（若需要点赞数作假的话，用此函数）
	 * @param int $post_id
	 */
	public function decLikes($post_id){
		Posts::model()->inc($post_id, 'likes', -1);
	}
	
	/**
	 * 是否已收藏
	 * @param int $post_id
	 * @param null|int $user_id
	 * @return boolean
	 */
	public function isFavored($post_id, $user_id = null){
		if($user_id === null){
			if(\F::app()->current_user){
				$user_id = \F::app()->current_user;
			}else{
				return false;
			}
		}
		
		if(Favourites::model()->find(array($user_id, $post_id))){
			return true;
		}else{
			return false;
		}
	}
	
	
	
	/**
	 * 设置一个文章属性值
	 * @param int $post_id
	 * @param string $alias
* @param mixed $value
	 * @return boolean
	 */
	public function setPropValueByAlias($alias, $value, $post_id){
		return Prop::model()->setPropValueByAlias('post_id', $post_id, $alias, $value, array(
			'varchar'=>'fay\models\tables\PostPropVarchar',
			'int'=>'fay\models\tables\PostPropInt',
			'text'=>'fay\models\tables\PostPropText',
		));
	}
	
	/**
	 * 获取一个文章属性值
	 * @param int $post_id
	 * @param string $alias
	 */
	public function getPropValueByAlias($alias, $post_id){
		return Prop::model()->getPropValueByAlias('post_id', $post_id, $alias, array(
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
		Likes::model()->delete('post_id = '.$post_id);
		Favourites::model()->delete('post_id = '.$post_id);
		
		//刷新对应tags的count值
		Tag::model()->refreshCountByTagId($tag_ids);
	}
	
	/**
	 * 获取文章对应tags
	 * @param int $post_id
	 */
	public function getTags($post_id){
		$sql = new Sql();
		return $sql->from('posts_tags', 'pt', '')
			->joinLeft('tags', 't', 'pt.tag_id = t.id', 'id,title')
			->where(array(
				'pt.post_id = ?'=>$post_id,
			))
			->order('t.`count`')
			->fetchAll();
	}
	
	/**
	 * 根据文章属性、分类，获取对应的文章（仅支持下拉，多选属性，不支持文本属性）<br>
	 * 分类包含所有子分类
	 * @param int|string $prop_alias 可传入属性ID或者alias
	 * @param string $prop_value 属性值
	 * @param int $limit 返回文章数
	 * @param string $field 返回posts表中的字段（cat_title）默认返回
	 * @param string $order 排序字段
	 */
	public function getByProp($prop, $prop_value, $limit = 10, $cat_id = 0, $field = 'id,title,thumbnail,abstract', $order = 'p.is_top DESC, p.sort, p.publish_time DESC'){
		if(!is_numeric($prop)){
			$prop = Prop::model()->getIdByAlias($prop);
		}
		$sql = new Sql();
		$sql->from('posts', 'p', $field)
			->joinLeft('categories', 'c', 'p.cat_id = c.id', 'title AS cat_title')
			->where(array(
				'p.deleted = 0',
				'p.publish_time < '.\F::app()->current_time,
				'p.status = '.Posts::STATUS_PUBLISHED,
				'pi.content = '.$prop_value,
			))
			->joinLeft('post_prop_int', 'pi', array(
				'pi.prop_id = '.$prop,
				'pi.post_id = p.id',
			))
			->order($order)
			->group('p.id')
			->limit($limit)
		;
		if(!empty($cat_id)){
			$cat = Category::model()->get($cat_id);
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
			return String::nl2p($post['content']);
		}else{
			return $post['content'];
		}
	}
	
	/**
	 * 判断当前登录用户是否对该文章有编辑权限
	 * @param int $post_id 文章ID
	 * @param int $new_status 更新后的状态，不传则不做验证
	 * @param int $new_cat_id 更新后的分类，不传则不做验证
	 */
	public static function checkEditPermission($post_id, $new_status = null, $new_cat_id = null){
		if(substr(\F::config()->get('session_namespace'), -6) == '_admin'){
			//后台用户
			/**
			 * 是否启用分类权限
			 */
			$role_cats = is_bool(\F::app()->role_cats) ? \F::app()->role_cats : Option::get('system:role_cats');
			/**
			 * 是否启用文章审核
			 */
			$post_review = is_bool(\F::app()->post_review) ? \F::app()->post_review : Option::get('system:post_review');
			//没有编辑权限，直接返回错误
			if(!\F::app()->checkPermission('admin/post/edit')){
				return array(
					'status'=>0,
					'message'=>'您无文章编辑权限！',
					'error_code'=>'permission-denied:no-edit-action-allowed',
				);
			}
			
			$post = Posts::model()->find($post_id, 'status,cat_id');
			//若系统开启分类权限且当前用户非超级管理员，且当前用户无此分类操作权限，则文章不可编辑
			if($role_cats &&
				\F::session()->get('role') != Users::ROLE_SUPERADMIN &&
				!in_array($post['cat_id'], \F::session()->get('role_cats'))){
				return array(
					'status'=>0,
					'message'=>'您无权编辑该分类下的文章！',
					'error_code'=>'permission-denied:category-denied',
				);
			}
			
			//文章分类被编辑，且当前用户不是超级管理员，且无权限对新分类进行编辑
			if($new_cat_id &&
				\F::session()->get('role') != Users::ROLE_SUPERADMIN &&
				$role_cats &&
				!in_array($new_cat_id, \F::session()->get('role_cats'))){
				return array(
					'status'=>0,
					'message'=>'您无权将该文章设置为当前分类',
					'error_code'=>'permission-denied:category-denied',
				);
			}
			
			//文章状态被编辑
			if($new_status){
				//若系统开启文章审核功能；且文章原状态不是“通过审核”，被修改为“通过审核”；且该用户无审核权限 - 报错
				if($post_review &&
					$post['status'] != Posts::STATUS_REVIEWED && $new_status == Posts::STATUS_REVIEWED &&
					!\F::app()->checkPermission('admin/post/review')
				){
					return array(
						'status'=>0,
						'message'=>'您无权将该文章设置为“通过审核”状态',
						'error_code'=>'permission-denied:status-denied',
					);
				}
				//若系统开启文章审核功能；文章原状态不是“已发布”，被修改为“已发布”；且该用户无发布权限- 报错
				if($post_review &&
					$post['status'] != Posts::STATUS_PUBLISHED && $new_status == Posts::STATUS_PUBLISHED &&
					!\F::app()->checkPermission('admin/post/publish')
				){
					return array(
						'status'=>0,
						'message'=>'您无权将该文章设置为“已发布”状态',
						'error_code'=>'permission-denied:status-denied',
					);
				}
			}
			return array(
				'status'=>1,
			);
		}else{
			//前台用户，只要是自己的文章都能编辑
			$post = Posts::model()->find($post_id, 'user_id');
			if($post['user_id'] != \F::app()->current_user){
				return array(
					'status'=>0,
					'message'=>'您无权编辑该文章！',
					'error_code'=>'permission-denied',
				);
			}else{
				return array(
					'status'=>1,
				);
			}
		}
	}
	
	/**
	 * 判断当前登录用户是否对该文章有删除权限
	 * @param int $post_id 文章ID
	 */
	public static function checkDeletePermission($post_id){
		if(substr(\F::config()->get('session_namespace'), -6) == '_admin'){
			//后台用户
			//没有删除权限，直接返回错误
			if(!\F::app()->checkPermission('admin/post/delete')){
				return array(
					'status'=>0,
					'message'=>'您无删除文章权限！',
					'error_code'=>'permission-denied:no-delete-action-allowed',
				);
			}
			$post = Posts::model()->find($post_id, 'status,cat_id');
			
			/**
			 * 是否启用分类权限
			 */
			$role_cats = is_bool(\F::app()->role_cats) ? \F::app()->role_cats : Option::get('system:role_cats');
			//若系统开启分类权限且当前用户非超级管理员，且当前用户无此分类操作权限，则文章不可删除
			if($role_cats &&
				\F::session()->get('role') != Users::ROLE_SUPERADMIN &&
				!in_array($post['cat_id'], \F::session()->get('role_cats'))){
				return array(
					'status'=>0,
					'message'=>'您无权删除该分类下的文章！',
					'error_code'=>'permission-denied:category-denied',
				);
			}
			return array(
				'status'=>1,
			);
		}else{
			//前台用户，只要是自己的文章都能删除
			$post = Posts::model()->find($post_id, 'user_id');
			if($post['user_id'] != \F::app()->current_user){
				return array(
					'status'=>0,
					'message'=>'您无权删除该文章！',
					'error_code'=>'permission-denied',
				);
			}else{
				return array(
					'status'=>1,
				);
			}
		}
	}
	
	/**
	 * 判断当前登录用户是否对该文章有还原权限
	 * @param int $post_id 文章ID
	 */
	public static function checkUndeletePermission($post_id){
		if(substr(\F::config()->get('session_namespace'), -6) == '_admin'){
			//后台用户
			//没有还原权限，直接返回错误
			if(!\F::app()->checkPermission('admin/post/undelete')){
				return array(
					'status'=>0,
					'message'=>'您无删除文章权限！',
					'error_code'=>'permission-denied:no-delete-action-allowed',
				);
			}
			$post = Posts::model()->find($post_id, 'status,cat_id');
			
			/**
			 * 是否启用分类权限
			 */
			$role_cats = is_bool(\F::app()->role_cats) ? \F::app()->role_cats : Option::get('system:role_cats');
			//若系统开启分类权限且当前用户非超级管理员，且当前用户无此分类操作权限，则文章不可还原
			if($role_cats &&
				\F::session()->get('role') != Users::ROLE_SUPERADMIN &&
				!in_array($post['cat_id'], \F::session()->get('role_cats'))){
				return array(
					'status'=>0,
					'message'=>'您无权还原该分类下的文章！',
					'error_code'=>'permission-denied:category-denied',
				);
			}
			return array(
				'status'=>1,
			);
		}else{
			//前台用户，只要是自己的文章都能还原
			$post = Posts::model()->find($post_id, 'user_id');
			if($post['user_id'] != \F::app()->current_user){
				return array(
					'status'=>0,
					'message'=>'您无权还原该文章！',
					'error_code'=>'permission-denied',
				);
			}else{
				return array(
					'status'=>1,
				);
			}
		}
	}
	
	/**
	 * 判断当前登录用户是否对该文章有永久删除权限
	 * @param int $post_id 文章ID
	 */
	public static function checkRemovePermission($post_id){
		if(substr(\F::config()->get('session_namespace'), -6) == '_admin'){
			//后台用户
			//没有永久删除权限，直接返回错误
			if(!\F::app()->checkPermission('admin/post/remove')){
				return array(
					'status'=>0,
					'message'=>'您无永久删除文章权限！',
					'error_code'=>'permission-denied:no-delete-action-allowed',
				);
			}
			$post = Posts::model()->find($post_id, 'status,cat_id');
			
			/**
			 * 是否启用分类权限
			 */
			$role_cats = is_bool(\F::app()->role_cats) ? \F::app()->role_cats : Option::get('system:role_cats');
			//若系统开启分类权限且当前用户非超级管理员，且当前用户无此分类操作权限，则文章不可永久删除
			if($role_cats &&
				\F::session()->get('role') != Users::ROLE_SUPERADMIN &&
				!in_array($post['cat_id'], \F::session()->get('role_cats'))){
				return array(
					'status'=>0,
					'message'=>'您无权永久删除该分类下的文章！',
					'error_code'=>'permission-denied:category-denied',
				);
			}
			return array(
				'status'=>1,
			);
		}else{
			//前台用户，只要是自己的文章都能永久删除
			$post = Posts::model()->find($post_id, 'user_id');
			if($post['user_id'] != \F::app()->current_user){
				return array(
					'status'=>0,
					'message'=>'您无权永久删除该文章！',
					'error_code'=>'permission-denied',
				);
			}else{
				return array(
					'status'=>1,
				);
			}
		}
	}
}
