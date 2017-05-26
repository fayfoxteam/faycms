<?php
namespace cms\services\post;

use cms\models\tables\PostHistoriesTable;
use cms\models\tables\PropsTable;
use cms\services\prop\PropService;
use fay\core\Service;
use fay\core\Sql;
use fay\helpers\ArrayHelper;
use fay\helpers\FieldHelper;
use fay\helpers\StringHelper;
use cms\models\tables\PostsTable;
use cms\models\tables\PostsCategoriesTable;
use cms\models\tables\PostsFilesTable;
use cms\models\tables\PostsTagsTable;
use cms\models\tables\PostPropIntTable;
use cms\models\tables\PostPropVarcharTable;
use cms\models\tables\PostPropTextTable;
use cms\models\tables\PostLikesTable;
use cms\models\tables\PostMetaTable;
use fay\helpers\RequestHelper;
use cms\models\tables\PostFavoritesTable;
use cms\models\tables\PostExtraTable;
use cms\services\CategoryService;
use cms\services\file\FileService;
use cms\services\OptionService;
use cms\services\user\UserService;

/**
 * 文章服务
 */
class PostService extends Service{
    /**
     * 文章创建后事件
     */
    const EVENT_CREATED = 'after_post_created';
    
    /**
     * 文章更新后事件
     */
    const EVENT_UPDATED = 'after_post_updated';
    
    /**
     * 文章被删除后事件
     */
    const EVENT_DELETED = 'after_post_deleted';
    
    /**
     * 文章被还原后事件
     */
    const EVENT_UNDELETE = 'after_post_undelete';
    
    /**
     * 文章被物理删除事件
     */
    const EVENT_REMOVING = 'before_post_removed';
    
    /**
     * 允许在接口调用时返回的字段
     */
    public static $public_fields = array(
        'post'=>array(
            'id', 'cat_id', 'title', 'content', 'content_type', 'publish_time', 'thumbnail', 'abstract', 'delete_time', 'status',
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
            'id', 'description', 'thumbnail', 'url', 'width', 'height', 'is_image',
        ),
        'props'=>array(
            '*',//这里指定的是属性别名，取值视后台设定而定
        ),
        'meta'=>array(
            'comments', 'views', 'likes', 'favorites',
        ),
        'extra'=>array(
            'markdown', 'seo_title', 'seo_keywords', 'seo_description', 'source', 'source_link',
        ),
    );
    
    /**
     * 默认接口返回字段
     */
    public static $default_fields = array(
        'post'=>array(
            'fields'=>array(
                'id', 'title', 'content', 'content_type', 'publish_time', 'thumbnail', 'abstract',
            )
        ),
        'category'=>array(
            'fields'=>array(
                'id', 'title', 'alias',
            )
        ),
        'user'=>array(
            'fields'=>array(
                'id', 'nickname', 'avatar',
            )
        )
    );

    /**
     * @param string $class_name
     * @return PostService
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
     * @throws PostErrorException
     */
    public function create($post, $extra = array(), $user_id = null){
        //确定作者
        if($user_id === null){
            $user_id = \F::app()->current_user;
        }else if(!UserService::isUserIdExist($user_id)){
            throw new PostErrorException("指定用户ID[{$user_id}]不存在", 'user-id-is-not-exist');
        }
        
        //验证分类
        if(!empty($post['cat_id']) && !CategoryService::service()->isIdExist($post['cat_id'], '_system_post')){
            throw new PostErrorException("指定分类ID[{$post['cat_id']}]不存在");
        }
        
        $post['create_time'] = \F::app()->current_time;
        $post['update_time'] = \F::app()->current_time;
        $post['user_id'] = $user_id;
        empty($post['publish_time']) && $post['publish_time'] = \F::app()->current_time;
        $post['publish_date'] = date('Y-m-d', $post['publish_time']);
        
        //过滤掉多余的数据，并插入文章表
        $post_id = PostsTable::model()->insert($post, true);
        //获取文章状态，后面有用
        if(isset($post['status'])){
            $post_status = $post['status'];
        }else{
            $db_post = PostsTable::model()->find($post_id, 'status');
            $post_status = $db_post['status'];
        }
        //更新文章主分类文章数
        if(!empty($post['cat_id'])){
            PostCategoryService::service()->updatePrimaryCatCount(null, $post['cat_id'], null, $post_status);
        }
        
        //Meta
        $post_meta = array(
            'post_id'=>$post_id,
        );
        if(isset($extra['meta'])){
            $post_meta = $post_meta + $extra['meta'];
        }
        
        PostMetaTable::model()->insert($post_meta);
        
        //扩展信息
        $post_extra = array(
            'post_id'=>$post_id,
            'ip_int'=>RequestHelper::ip2int(\F::app()->ip),
        );
        if(isset($extra['extra'])){
            $post_extra = $post_extra + $extra['extra'];
        }
        //特殊处理下text字段
        if(empty($post_extra['markdown'])){
            $post_extra['markdown'] = '';
        }
        
        PostExtraTable::model()->insert($post_extra);
        
        //文章分类
        if(!empty($extra['categories'])){
            PostCategoryService::service()->setSecondaryCats(
                isset($post['cat_id']) ? $post['cat_id'] : 0,
                $extra['categories'],
                $post_id,
                null,
                $post_status
            );
        }
        //标签
        if(isset($extra['tags'])){
            PostTagService::service()->set($extra['tags'], $post_id, null, $post_status);
        }
        
        //附件
        if(isset($extra['files'])){
            $i = 0;
            foreach($extra['files'] as $file_id => $description){
                $i++;
                PostsFilesTable::model()->insert(array(
                    'file_id'=>$file_id,
                    'post_id'=>$post_id,
                    'description'=>$description,
                    'is_image'=>FileService::isImage($file_id),
                    'sort'=>$i,
                ));
            }
        }
        
        //设置属性
        if(isset($extra['props'])){
            PostPropService::service()->createPropSet($post_id, $extra['props']);
        }
        
        if($post_status == PostsTable::STATUS_PUBLISHED){
            //用户文章数加一
            PostUserCounterService::service()->incr($user_id);
        }
        
        //触发事件
        \F::event()->trigger(self::EVENT_CREATED, $post_id);
    
        //记录历史
        if(OptionService::get('system:save_post_history')){
            PostHistoryService::service()->create($post_id);
        }
        
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
     * @param bool $update_update_time 是否更新“最后更新时间”。默认为true
     * @return bool
     * @throws PostErrorException
     */
    public function update($post_id, $data, $extra = array(), $update_update_time = true){
        //获取原文章
        $old_post = PostsTable::model()->find($post_id, 'cat_id,user_id,delete_time,status');
        if(!$old_post){
            throw new PostErrorException('指定文章不存在');
        }
        if($old_post['delete_time']){
            throw new PostErrorException('已删除文章不允许编辑');
        }
        
        if($update_update_time){
            $data['update_time'] = \F::app()->current_time;
        }else if(isset($data['update_time'])){
            unset($data['update_time']);
        }
        
        //过滤掉多余的字段后更新
        PostsTable::model()->update($data, $post_id, true);
        
        //更新主分类文章数
        $primary_cat_id = isset($data['cat_id']) ? $data['cat_id'] : $old_post['cat_id'];
        PostCategoryService::service()->updatePrimaryCatCount(
            $old_post['cat_id'],
            isset($data['cat_id']) ? $data['cat_id'] : null,
            $old_post['status'],
            isset($data['status']) ? $data['status'] : null
        );
        
        //计数表
        if(!empty($extra['meta'])){
            //排除不可编辑的字段
            PostMetaTable::model()->update($extra['meta'], $post_id, true);
        }
        
        //扩展表
        if(!empty($extra['extra'])){
            //排除不可编辑的字段
            PostExtraTable::model()->update($extra['extra'], $post_id, true);
        }
        
        //若原文章未删除，更新用户及标签的文章数
        if($old_post['status'] == PostsTable::STATUS_PUBLISHED &&
            isset($data['status']) && $data['status'] != PostsTable::STATUS_PUBLISHED){
            //若原文章是“已发布”状态，且新状态不是“已发布”
            
            //用户文章数减一
            PostUserCounterService::service()->decr($old_post['user_id']);
        }else if($old_post['status'] != PostsTable::STATUS_PUBLISHED &&
            isset($data['status']) && $data['status'] == PostsTable::STATUS_PUBLISHED){
            //若原文章不是“已发布”状态，且新状态是“已发布”
            
            //用户文章数加一
            PostUserCounterService::service()->incr($old_post['user_id']);
        }
        
        //附加分类
        if(isset($extra['categories'])){
            //更新附加分类
            PostCategoryService::service()->setSecondaryCats(
                $primary_cat_id,
                $extra['categories'],
                $post_id,
                $old_post['status'],
                isset($data['status']) ? $data['status'] : null
            );
        }
        
        //标签
        if(isset($extra['tags'])){
            PostTagService::service()->set(
                $extra['tags'],
                $post_id,
                $old_post['status'],
                isset($data['status']) ? $data['status'] : null
            );
        }
        
        //附件
        if(isset($extra['files'])){
            //删除已被删除的图片
            if($extra['files']){
                PostsFilesTable::model()->delete(array(
                    'post_id = ?'=>$post_id,
                    'file_id NOT IN (?)'=>array_keys($extra['files']),
                ));
            }else{
                PostsFilesTable::model()->delete(array(
                    'post_id = ?'=>$post_id,
                ));
            }
            //获取已存在的图片
            $old_files_ids = PostsFilesTable::model()->fetchCol('file_id', array(
                'post_id = ?'=>$post_id,
            ));
            $i = 0;
            foreach($extra['files'] as $file_id => $description){
                $i++;
                if(in_array($file_id, $old_files_ids)){
                    PostsFilesTable::model()->update(array(
                        'description'=>$description,
                        'sort'=>$i,
                    ), array(
                        'post_id = ?'=>$post_id,
                        'file_id = ?'=>$file_id,
                    ));
                }else{
                    PostsFilesTable::model()->insert(array(
                        'post_id'=>$post_id,
                        'file_id'=>$file_id,
                        'description'=>$description,
                        'sort'=>$i,
                        'is_image'=>FileService::isImage($file_id),
                    ));
                }
            }
        }
        
        //附加属性
        if(isset($extra['props'])){
            PostPropService::service()->updatePropSet($post_id, $extra['props']);
        }
        
        //触发事件
        \F::event()->trigger(self::EVENT_UPDATED, array(
            'post_id'=>$post_id,
            'old_status'=>$old_post['status'],
        ));
    
        //记录历史
        if(OptionService::get('system:save_post_history')){
            PostHistoryService::service()->create($post_id);
        }
        
        return true;
    }
    
    /**
     * 彻底删除一篇文章
     * @param $post_id
     * @return bool
     */
    public function remove($post_id){
        //获取文章删除状态
        $post = PostsTable::model()->find($post_id, 'user_id,delete_time,status');
        if(!$post){
            return false;
        }
        
        //触发事件
        \F::event()->trigger(self::EVENT_REMOVING, $post_id);
        
        //删除文章
        PostsTable::model()->delete($post_id);
        
        //若文章未通过回收站被直接删除，且文章“已发布”
        if(!$post['delete_time'] && $post['status'] == PostsTable::STATUS_PUBLISHED){
            //则作者文章数减一
            PostUserCounterService::service()->decr($post['user_id']);
            
            //相关标签文章数减一
            PostTagService::service()->decr($post_id);
            
            //分类文章数减一
            PostCategoryService::service()->decr($post_id);
        }
        //删除文章与标签的关联关系
        PostsTagsTable::model()->delete('post_id = ' . $post_id);
        
        //删除文章附加分类
        PostsCategoriesTable::model()->delete('post_id = '.$post_id);
        
        //删除文章附件（只是删除对应关系，并不删除附件文件）
        PostsFilesTable::model()->delete('post_id = '.$post_id);
        
        //删除文章可能存在的自定义属性
        PostPropIntTable::model()->delete('relation_id = '.$post_id);
        PostPropVarcharTable::model()->delete('relation_id = '.$post_id);
        PostPropTextTable::model()->delete('relation_id = '.$post_id);
        
        //删除关注，收藏列表
        PostLikesTable::model()->delete('post_id = '.$post_id);
        PostFavoritesTable::model()->delete('post_id = '.$post_id);
        
        //删除文章meta信息
        PostMetaTable::model()->delete('post_id = ' . $post_id);
        
        //删除文章扩展信息
        PostExtraTable::model()->delete('post_id = ' . $post_id);
        
        //删除文章历史
        PostHistoriesTable::model()->delete('post_id = ' . $post_id);
        
        return true;
    }
    
    /**
     * 删除一篇文章
     * @param int $post_id 文章ID
     * @return bool
     */
    public function delete($post_id){
        $post = PostsTable::model()->find($post_id, 'user_id,delete_time,status');
        if(!$post || $post['delete_time']){
            return false;
        }
        
        //标记为已删除
        PostsTable::model()->update(array(
            'delete_time'=>\F::app()->current_time,
        ), $post_id);
        
        //若被删除文章是“已发布”状态
        if($post['status'] == PostsTable::STATUS_PUBLISHED){
            //用户文章数减一
            PostUserCounterService::service()->decr($post['user_id']);
            
            //相关标签文章数减一
            PostTagService::service()->decr($post_id);
            
            //相关分类文章数减一
            PostCategoryService::service()->decr($post_id);
        }
        
        //触发事件
        \F::event()->trigger(self::EVENT_DELETED, array($post_id));
        
        return true;
    }
    
    /**
     * 还原一篇文章
     * @param int $post_id 文章ID
     * @return bool
     */
    public function undelete($post_id){
        $post = PostsTable::model()->find($post_id, 'user_id,delete_time,status');
        if(!$post || !$post['delete_time']){
            return false;
        }
        
        //标记为未删除
        PostsTable::model()->update(array(
            'delete_time'=>0
        ), $post_id);
        
        //若被还原文章是“已发布”状态
        if($post['status'] == PostsTable::STATUS_PUBLISHED){
            //用户文章数减一
            PostUserCounterService::service()->incr($post['user_id']);
            
            //相关标签文章数加一
            PostTagService::service()->incr($post_id);
            
            //相关分类文章数加一
            PostCategoryService::service()->incr($post_id);
        }
        
        //触发事件
        \F::event()->trigger(self::EVENT_UNDELETE, array($post_id));
        
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
     *  - user.*系列可指定作者信息，格式参照\cms\services\user\UserService::get()
     *  - categories.*系列可指定附加分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
     *  - category.*系列可指定主分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
     * @param int|string|array $cat 若指定分类（可以是id，alias或者包含left_value, right_value值的数组），
     *    则只会在此分类及其子分类下搜索该篇文章<br>
     *    该功能主要用于多栏目不同界面的时候，文章不要显示到其它栏目去
     * @param bool $only_published 若为true，则只在已发布的文章里搜索。默认为true
     * @return array|bool
     */
    public function get($id, $fields = 'post.*', $cat = null, $only_published = true){
        //解析$fields
        $fields = FieldHelper::parse($fields, 'post', self::$public_fields);
        if(empty($fields['post'])){
            //若未指定返回字段，返回所有允许的字段
            $fields['post'] = self::$default_fields['post'];
        }
        
        $post_fields = $fields['post']['fields'];
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
            $sql->where(PostsTable::getPublishedConditions('p'));
        }
        
        //若指定了分类，加上分类条件限制
        if($cat){
            if(!is_array($cat)){
                $cat = CategoryService::service()->get($cat, 'left_value,right_value');
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
            //如果有缩略图，将缩略图图片ID转为图片对象
            $post['thumbnail'] = $this->formatThumbnail(
                $post['thumbnail'],
                isset($fields['post']['extra']['thumbnail']) ? $fields['post']['extra']['thumbnail'] : ''
            );
        }
        
        $return = array(
            'post'=>$post,
        );
        
        //meta
        if(!empty($fields['meta'])){
            $return['meta'] = PostMetaService::service()->get($id, $fields['meta']);
        }
        
        //扩展信息
        if(!empty($fields['extra'])){
            $return['extra'] = PostExtraService::service()->get($id, $fields['extra']);
        }
        
        //设置一下SEO信息
        if(isset($fields['extra']) && in_array('seo_title', $fields['extra']['fields']) && empty($return['extra']['seo_title'])){
            $return['extra']['seo_title'] = $post['title'];
        }
        if(isset($fields['extra']) && in_array('seo_keywords', $fields['extra']['fields']) && empty($return['extra']['seo_keywords'])){
            $return['extra']['seo_keywords'] = str_replace(array(
                ' ', '|', '，'
            ), ',', $post['title']);
        }
        if(isset($fields['extra']) && in_array('seo_description', $fields['extra']['fields']) && empty($return['extra']['seo_description'])){
            $return['extra']['seo_description'] = $post['abstract'] ? $post['abstract'] : trim(mb_substr(str_replace(array("\r\n", "\r", "\n"), ' ', strip_tags($post['content'])), 0, 150));
        }
        
        //作者信息
        if(!empty($fields['user'])){
            $return['user'] = UserService::service()->get($post['user_id'], $fields['user']);
        }
        
        //标签
        if(!empty($fields['tags'])){
            $return['tags'] = PostTagService::service()->get($id, $fields['tags']);
        }
        
        //附件
        if(!empty($fields['files'])){
            $return['files'] = PostFileService::service()->get($id, $fields['files']);
        }
        
        //附加属性
        if(!empty($fields['props'])){
            if(in_array('*', $fields['props']['fields'])){
                $props = null;
            }else{
                $props = PropService::service()->mget($fields['props']['fields'], PropsTable::USAGE_POST_CAT);
            }
            $return['props'] = PostPropService::service()->getPropSet($id, $props);
        }
        
        //附加分类
        if(!empty($fields['categories'])){
            $return['categories'] = PostCategoryService::service()->get($id, $fields['categories']);
        }
        
        //主分类
        if(!empty($fields['category'])){
            $return['category'] = CategoryService::service()->get($post['cat_id'], $fields['category']);
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
            if(!in_array($f, $fields['post']['fields']) && in_array($f, $post_fields)){
                unset($return['post'][$f]);
            }
        }
        
        return $return;
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
        $post = PostsTable::model()->find($post_id, 'id,cat_id,publish_time,sort');
        //解析字段
        $fields = FieldHelper::parse($fields, 'post', PostService::$public_fields);
        
        $post_fields = $fields['post']['fields'];
        if(!in_array('sort', $post_fields)){
            $post_fields[] = 'sort';
        }
        if(!in_array('publish_time', $post_fields)){
            $post_fields[] = 'publish_time';
        }
        $prev_post = $sql->from(array('p'=>'posts'), $post_fields)
            ->where(array(
                'p.cat_id = '.$post['cat_id'],
                "p.publish_time >= {$post['publish_time']}",
                "p.sort >= {$post['sort']}",
                "p.id != {$post['id']}",
            ))
            ->where(PostsTable::getPublishedConditions('p'))
            ->order('is_top, sort DESC, publish_time')
            ->fetchRow();
        if($prev_post){
            if($prev_post['publish_time'] == $post['publish_time'] && $prev_post['sort'] == $post['sort']){
                //当排序值和发布时间都一样的情况下，可能出错，需要重新根据ID搜索（不太可能发布时间都一样的）
                $prev_post = $sql->from(array('p'=>'posts'), 'id,title,sort,publish_time')
                    ->where(array(
                        'p.cat_id = '.$post['cat_id'],
                        "p.publish_time = {$post['publish_time']}",
                        "p.sort = {$post['sort']}",
                        "p.id > {$post['id']}",
                    ))
                    ->where(PostsTable::getPublishedConditions('p'))
                    ->order('id ASC')
                    ->fetchRow();
            }
            if(!in_array('sort', $fields['post']['fields'])){
                unset($prev_post['sort']);
            }
            if(!in_array('publish_time', $fields['post']['fields'])){
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
        $post = PostsTable::model()->find($post_id, 'id,cat_id,publish_time,sort');
        //解析字段
        $fields = FieldHelper::parse($fields, 'post', PostService::$public_fields);
        
        $post_fields = $fields['post']['fields'];
        if(!in_array('sort', $post_fields)){
            $post_fields[] = 'sort';
        }
        if(!in_array('publish_time', $post_fields)){
            $post_fields[] = 'publish_time';
        }
        $next_post = $sql->from(array('p'=>'posts'), $post_fields)
            ->where(array(
                'p.cat_id = '.$post['cat_id'],
                "p.publish_time <= {$post['publish_time']}",
                "p.sort <= {$post['sort']}",
                "p.id != {$post['id']}",
            ))
            ->where(PostsTable::getPublishedConditions('p'))
            ->order('is_top, sort DESC, publish_time')
            ->fetchRow();
        if($next_post){
            if($next_post['publish_time'] == $post['publish_time'] && $next_post['sort'] == $post['sort']){
                //当排序值和发布时间都一样的情况下，可能出错，需要重新根据ID搜索（不太可能发布时间都一样的）
                $next_post = $sql->from(array('p'=>'posts'), 'id,title,sort,publish_time')
                    ->where(array(
                        'p.cat_id = '.$post['cat_id'],
                        "p.publish_time = {$post['publish_time']}",
                        "p.sort = {$post['sort']}",
                        "p.id < {$post['id']}",
                    ))
                    ->where(PostsTable::getPublishedConditions('p'))
                    ->order('id ASC')
                    ->fetchRow();
            }
            if(!in_array('sort', $fields['post']['fields'])){
                unset($next_post['sort']);
            }
            if(!in_array('publish_time', $fields['post']['fields'])){
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
     */
    public function getByProp($prop, $prop_value, $limit = 10, $cat_id = 0, $fields = 'id,title,thumbnail,abstract', $order = 'p.is_top DESC, p.sort DESC, p.publish_time DESC'){
        if(!StringHelper::isInt($prop)){
            $prop = PropService::service()->getIdByAlias($prop);
        }
        $sql = new Sql();
        $sql->from(array('p'=>'posts'), $fields)
            ->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title')
            ->where(PostsTable::getPublishedConditions('p'))
            ->where(array(
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
            $cat = CategoryService::service()->get($cat_id);
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
        if($post['content_type'] == PostsTable::CONTENT_TYPE_MARKDOWN){
            return $post['content'];
        }else if($post['content_type'] == PostsTable::CONTENT_TYPE_TEXTAREA){
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
     * @throws PostErrorException
     */
    public static function checkEditPermission($post, $new_status = null, $new_cat_id = null, $user_id = null){
        if(!is_array($post)){
            $post = PostsTable::model()->find($post, 'user_id,cat_id,status');
        }
        $user_id || $user_id = \F::app()->current_user;
        
        if(!$post){
            throw new PostErrorException('指定文章不存在');
        }
        
        if($post['user_id'] == $user_id){
            //自己的文章总是有权限还原的
            return true;
        }
        
        if(UserService::service()->isAdmin($user_id) &&
            UserService::service()->checkPermission('cms/admin/post/edit', $user_id) &&
            PostCategoryService::service()->isAllowedCat($post['cat_id'], $user_id)){
            //是管理员，有还原权限，且有当前文章的分类权限
            
            if($new_cat_id && !PostCategoryService::service()->isAllowedCat($new_cat_id, $user_id)){
                //若指定了新分类，判断用户是否对新分类有编辑权限
                return false;
            }
            
            //文章状态被编辑
            if($new_status){
                //若系统开启文章审核功能；且文章原状态不是“通过审核”，被修改为“通过审核”；且该用户无审核权限，返回false
                if(OptionService::get('system:post_review') &&
                    $post['status'] != PostsTable::STATUS_REVIEWED && $new_status == PostsTable::STATUS_REVIEWED &&
                    !\F::app()->checkPermission('cms/admin/post/review')
                ){
                    return false;
                }
                //若系统开启文章审核功能；文章原状态不是“已发布”，被修改为“已发布”；且该用户无发布权限，返回false
                if(OptionService::get('system:post_review') &&
                    $post['status'] != PostsTable::STATUS_PUBLISHED && $new_status == PostsTable::STATUS_PUBLISHED &&
                    !\F::app()->checkPermission('cms/admin/post/publish')
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
     * @throws PostErrorException
     */
    public static function checkDeletePermission($post, $user_id = null){
        if(!is_array($post)){
            $post = PostsTable::model()->find($post, 'user_id,cat_id');
        }
        $user_id || $user_id = \F::app()->current_user;
        
        if(!$post){
            throw new PostErrorException('指定文章不存在');
        }
        
        if($post['user_id'] == $user_id){
            //自己的文章总是有权限删除的
            return true;
        }
        
        if(UserService::service()->isAdmin($user_id) &&
            UserService::service()->checkPermission('cms/admin/post/delete', $user_id) &&
            PostCategoryService::service()->isAllowedCat($post['cat_id'], $user_id)){
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
     * @throws PostErrorException
     */
    public static function checkUndeletePermission($post, $user_id = null){
        if(!is_array($post)){
            $post = PostsTable::model()->find($post, 'user_id,cat_id');
        }
        $user_id || $user_id = \F::app()->current_user;
        
        if(!$post){
            throw new PostErrorException('指定文章不存在');
        }
        
        if($post['user_id'] == $user_id){
            //自己的文章总是有权限还原的
            return true;
        }
        
        if(UserService::service()->isAdmin($user_id) &&
            UserService::service()->checkPermission('cms/admin/post/undelete', $user_id) &&
            PostCategoryService::service()->isAllowedCat($post['cat_id'], $user_id)){
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
     * @throws PostErrorException
     */
    public static function checkRemovePermission($post, $user_id = null){
        if(!is_array($post)){
            $post = PostsTable::model()->find($post, 'user_id,cat_id');
        }
        $user_id || $user_id = \F::app()->current_user;
        
        if(!$post){
            throw new PostErrorException('指定文章不存在');
        }
        
        if($post['user_id'] == $user_id){
            //自己的文章总是有权限永久删除的
            return true;
        }
        
        if(UserService::service()->isAdmin($user_id) &&
            UserService::service()->checkPermission('cms/admin/post/remove', $user_id) &&
            PostCategoryService::service()->isAllowedCat($post['cat_id'], $user_id)){
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
            $post = PostsTable::model()->find($post_id, 'delete_time,publish_time,status');
            if($post['delete_time'] || $post['publish_time'] > \F::app()->current_time || $post['status'] != PostsTable::STATUS_PUBLISHED){
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
     *  - user.*系列可指定作者信息，格式参照\cms\services\user\UserService::get()
     *  - categories.*系列可指定附加分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
     *  - category.*系列可指定主分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
     * @param bool $only_published 若为true，则只在已发布的文章里搜索。默认为false
     * @param bool $index_key 是否用文章ID作为键返回，默认为false
     * @return array
     * @throws PostErrorException
     * @throws \fay\core\ErrorException
     */
    public function mget($post_ids, $fields, $only_published = false, $index_key = false){
        if(!$post_ids){
            return array();
        }
        //解析$fields
        $fields = FieldHelper::parse($fields, 'post', self::$public_fields);
        if(empty($fields['post'])){
            //若未指定返回字段，返回所有允许的字段
            $fields['post'] = self::$default_fields['post'];
        }
        
        $post_fields = $fields['post']['fields'];
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
        $sql->from(array('p'=>PostsTable::model()->getTableName()), $post_fields)
            ->where('id IN (?)', $post_ids);
        
        //仅搜索已发布的文章
        if($only_published){
            $sql->where(PostsTable::getPublishedConditions('p'));
        }
        
        $posts = $sql->fetchAll();
        
        if(!$posts){
            return array();
        }
        
        $posts = ArrayHelper::column($posts, null, 'id');
        
        $return = array();
        //以传入文章ID顺序返回文章结构
        foreach($post_ids as $pid){
            if(isset($posts[$pid])){
                if(isset($posts[$pid]['thumbnail'])){
                    //如果有缩略图，将缩略图图片ID转为图片对象
                    $posts[$pid]['thumbnail'] = $this->formatThumbnail(
                        $posts[$pid]['thumbnail'],
                        isset($fields['post']['extra']['thumbnail']) ? $fields['post']['extra']['thumbnail'] : ''
                    );
                }
                $return[$pid] = array(
                    'post'=>$posts[$pid]
                );
            }
        }
        
        //meta
        if(!empty($fields['meta'])){
            PostMetaService::service()->assemble($return, $fields['meta']);
        }
        
        //扩展信息
        if(!empty($fields['extra'])){
            PostExtraService::service()->assemble($return, $fields['extra']);
        }
        
        //标签
        if(!empty($fields['tags'])){
            PostTagService::service()->assemble($return, $fields['tags']);
        }
        
        //附件
        if(!empty($fields['files'])){
            PostFileService::service()->assemble($return, $fields['files']);
        }
        
        //附加分类
        if(!empty($fields['categories'])){
            PostCategoryService::service()->assembleSecondaryCats($return, $fields['categories']);
        }
        
        //主分类
        if(!empty($fields['category'])){
            PostCategoryService::service()->assemblePrimaryCat($return, $fields['category']);
        }
        
        //附加属性
        if(!empty($fields['props'])){
            PostPropService::service()->assemble($return, $fields['props']);
        }
        
        //作者信息
        if(!empty($fields['user'])){
            PostUserService::service()->assemble($return, $fields['user']);
        }
        
        foreach($return as $k => $post){
            //过滤掉那些未指定返回，但出于某些原因先搜出来的字段
            foreach(array('id', 'user_id', 'cat_id') as $f){
                if(!in_array($f, $fields['post']['fields']) && in_array($f, $post_fields)){
                    unset($post['post'][$f]);
                }
            }
            
            $return[$k] = $post;
        }
        
        if($index_key){
            return $return;
        }else{
            return array_values($return);
        }
    }
    
    /**
     * 批量发布
     * @param array $post_ids 文章ID构成的一维数组
     * @return array
     */
    public function batchPublish($post_ids){
        //获取未发布文章
        $unpublished_posts = PostsTable::model()->fetchAll(array(
            'id IN (?)'=>$post_ids,
            'status != ' . PostsTable::STATUS_PUBLISHED,
        ), 'id,user_id');
        if(!$unpublished_posts){
            //没有符合条件的文章
            return array();
        }
        
        $unpublished_post_ids = ArrayHelper::column($unpublished_posts, 'id');
        
        PostsTable::model()->update(array(
            'status'=>PostsTable::STATUS_PUBLISHED,
        ), array(
            'id IN (?)'=>$unpublished_post_ids,
        ));
        
        //递增分类文章数
        PostCategoryService::service()->incr($unpublished_post_ids);
        
        //递增标签文章数
        PostTagService::service()->incr($unpublished_post_ids);
        
        //递增用户文章数
        $count_map = ArrayHelper::countValues(ArrayHelper::column($unpublished_posts, 'user_id'));
        foreach($count_map as $num => $sub_user_ids){
            PostUserCounterService::service()->incr($sub_user_ids, $num);
        }
        
        return $unpublished_post_ids;
    }
    
    /**
     * 批量标记为草稿
     * @param array $post_ids 文章ID构成的一维数组
     * @return array
     */
    public function batchDraft($post_ids){
        //获取不是草稿的文章
        $not_draft_posts = PostsTable::model()->fetchAll(array(
            'id IN (?)'=>$post_ids,
            'status != ' . PostsTable::STATUS_DRAFT,
        ), 'id,status,user_id');
        if(!$not_draft_posts){
            //没有符合条件的文章
            return array();
        }
        
        $not_draft_post_ids = ArrayHelper::column($not_draft_posts, 'id');
        
        //更新文章状态
        PostsTable::model()->update(array(
            'status'=>PostsTable::STATUS_DRAFT,
        ), array(
            'id IN (?)'=>$not_draft_post_ids,
        ));
        
        //获取这些文章中，是已发布状态的文章
        $published_post_ids = array();
        $published_user_ids = array();
        foreach($not_draft_posts as $p){
            if($p['status'] == PostsTable::STATUS_PUBLISHED){
                $published_post_ids[] = $p['id'];
                $published_user_ids[] = $p['user_id'];
            }
        }
        
        if($published_post_ids){
            //递减分类文章数
            PostCategoryService::service()->decr($published_post_ids);
            
            //递减标签文章数
            PostTagService::service()->decr($published_post_ids);
            
            //递减用户文章数
            $count_map = ArrayHelper::countValues($published_user_ids);
            foreach($count_map as $num => $sub_user_ids){
                PostUserCounterService::service()->decr($sub_user_ids, $num);
            }
        }
        
        return $not_draft_post_ids;
    }
    
    /**
     * 批量标记为待审核
     * @param array $post_ids 文章ID构成的一维数组
     * @return array
     */
    public function batchPending($post_ids){
        //获取不是待审核状态的文章
        $not_pending_posts = PostsTable::model()->fetchAll(array(
            'id IN (?)'=>$post_ids,
            'status != ' . PostsTable::STATUS_PENDING,
        ), 'id,status,user_id');
        if(!$not_pending_posts){
            //没有符合条件的文章
            return array();
        }
        
        $not_pending_post_ids = ArrayHelper::column($not_pending_posts, 'id');
        
        //更新文章状态
        PostsTable::model()->update(array(
            'status'=>PostsTable::STATUS_PENDING,
        ), array(
            'id IN (?)'=>$not_pending_post_ids,
        ));
        
        //获取这些文章中，是已发布状态的文章
        $published_post_ids = array();
        $published_user_ids = array();
        foreach($not_pending_posts as $p){
            if($p['status'] == PostsTable::STATUS_PUBLISHED){
                $published_post_ids[] = $p['id'];
                $published_user_ids[] = $p['user_id'];
            }
        }
        
        if($published_post_ids){
            //递减分类文章数
            PostCategoryService::service()->decr($published_post_ids);
            
            //递减标签文章数
            PostTagService::service()->decr($published_post_ids);
            
            //递减用户文章数
            $count_map = ArrayHelper::countValues($published_user_ids);
            foreach($count_map as $num => $sub_user_ids){
                PostUserCounterService::service()->decr($sub_user_ids, $num);
            }
        }
        
        return $not_pending_post_ids;
    }
    
    /**
     * 批量标记为已审核
     * @param array $post_ids 文章ID构成的一维数组
     * @return array
     */
    public function batchReviewed($post_ids){
        //获取不是已审核状态的文章
        $not_reviewed_posts = PostsTable::model()->fetchAll(array(
            'id IN (?)'=>$post_ids,
            'status != ' . PostsTable::STATUS_REVIEWED,
        ), 'id,status,user_id');
        if(!$not_reviewed_posts){
            //没有符合条件的文章
            return array();
        }
        
        $not_reviewed_post_ids = ArrayHelper::column($not_reviewed_posts, 'id');
        
        //更新文章状态
        PostsTable::model()->update(array(
            'status'=>PostsTable::STATUS_REVIEWED,
        ), array(
            'id IN (?)'=>$not_reviewed_post_ids,
        ));
        
        //获取这些文章中，是已发布状态的文章
        $published_post_ids = array();
        $published_user_ids = array();
        foreach($not_reviewed_posts as $p){
            if($p['status'] == PostsTable::STATUS_PUBLISHED){
                $published_post_ids[] = $p['id'];
                $published_user_ids[] = $p['user_id'];
            }
        }
        
        if($published_post_ids){
            //递减分类文章数
            PostCategoryService::service()->decr($published_post_ids);
            
            //递减标签文章数
            PostTagService::service()->decr($published_post_ids);
            
            //递减用户文章数
            $count_map = ArrayHelper::countValues($published_user_ids);
            foreach($count_map as $num => $sub_user_ids){
                PostUserCounterService::service()->decr($sub_user_ids, $num);
            }
        }
        
        return $not_reviewed_post_ids;
    }
    
    /**
     * 批量删除
     * @param array $post_ids 文章ID构成的一维数组
     * @return array
     */
    public function batchDelete($post_ids){
        //获取未删除的文章
        $undelete_posts = PostsTable::model()->fetchAll(array(
            'id IN (?)'=>$post_ids,
            'delete_time = 0',
        ), 'id,status,user_id');
        if(!$undelete_posts){
            //没有符合条件的文章
            return array();
        }
        
        $undelete_post_ids = ArrayHelper::column($undelete_posts, 'id');
        
        //软删除文章
        PostsTable::model()->update(array(
            'delete_time'=>\F::app()->current_time,
        ), array(
            'id IN (?)'=>$undelete_post_ids,
        ));
        
        //获取这些文章中，是已发布状态的文章
        $published_post_ids = array();
        $published_user_ids = array();
        foreach($undelete_posts as $p){
            if($p['status'] == PostsTable::STATUS_PUBLISHED){
                $published_post_ids[] = $p['id'];
                $published_user_ids[] = $p['user_id'];
            }
        }
        
        if($published_post_ids){
            //递减分类文章数
            PostCategoryService::service()->decr($published_post_ids);
            
            //递减标签文章数
            PostTagService::service()->decr($published_post_ids);
            
            //递减用户文章数
            $count_map = ArrayHelper::countValues($published_user_ids);
            foreach($count_map as $num => $sub_user_ids){
                PostUserCounterService::service()->decr($sub_user_ids, $num);
            }
        }
        
        return $undelete_post_ids;
    }
    
    /**
     * 批量删除
     * @param array $post_ids 文章ID构成的一维数组
     * @return array
     */
    public function batchUndelete($post_ids){
        //获取已删除的文章
        $deleted_posts = PostsTable::model()->fetchAll(array(
            'id IN (?)'=>$post_ids,
            'delete_time != 0',
        ), 'id,status,user_id');
        if(!$deleted_posts){
            //没有符合条件的文章
            return array();
        }
        
        $deleted_post_ids = ArrayHelper::column($deleted_posts, 'id');
        
        //还原文章
        PostsTable::model()->update(array(
            'delete_time'=>0,
        ), array(
            'id IN (?)'=>$deleted_post_ids,
        ));
        
        //获取这些文章中，是已发布状态的文章
        $published_post_ids = array();
        $published_user_ids = array();
        foreach($deleted_posts as $p){
            if($p['status'] == PostsTable::STATUS_PUBLISHED){
                $published_post_ids[] = $p['id'];
                $published_user_ids[] = $p['user_id'];
            }
        }
        
        if($published_post_ids){
            //递增分类文章数
            PostCategoryService::service()->incr($published_post_ids);
            
            //递增标签文章数
            PostTagService::service()->incr($published_post_ids);
            
            //递增用户文章数
            $count_map = ArrayHelper::countValues($published_user_ids);
            foreach($count_map as $num => $sub_user_ids){
                PostUserCounterService::service()->incr($sub_user_ids, $num);
            }
        }
        
        return $deleted_post_ids;
    }
    
    private function formatThumbnail($thumbnail, $extra = ''){
        //如果有缩略图，将缩略图图片ID转为图片对象
        if(preg_match('/^(\d+)x(\d+)$/', $extra, $avatar_params)){
            return FileService::get($thumbnail, array(
                'spare'=>'post',
                'dw'=>$avatar_params[1],
                'dh'=>$avatar_params[2],
            ));
        }else{
            return FileService::get($thumbnail, array(
                'spare'=>'post',
            ));
        }
    }
    
    /**
     * 根据文章状态获取文章数
     * @param int $status 文章状态
     * @return string
     */
    public function getCount($status = null){
        $conditions = array('delete_time = 0');
        if($status !== null){
            $conditions['status = ?'] = $status;
        }
        $result = PostsTable::model()->fetchRow($conditions, 'COUNT(*)');
        return $result['COUNT(*)'];
    }
    
    /**
     * 获取已删除的文章数
     * @return string
     */
    public function getDeletedCount(){
        $result = PostsTable::model()->fetchRow('delete_time > 0', 'COUNT(*)');
        return $result['COUNT(*)'];
    }
}