<?php
namespace cms\services\post;

use fay\core\Loader;
use fay\core\Service;
use fay\core\Sql;
use fay\helpers\ArrayHelper;
use fay\helpers\FieldHelper;
use fay\helpers\StringHelper;
use cms\models\tables\CategoriesTable;
use cms\models\tables\PostsTable;
use cms\models\tables\PostsCategoriesTable;
use cms\services\OptionService;
use cms\services\user\UserRoleService;
use cms\models\tables\RolesTable;
use cms\models\tables\RolesCatsTable;
use cms\services\CategoryService;

class PostCategoryService extends Service{
    /**
     * 默认返回字段
     */
    public static $default_fields = array('id', 'title');
    
    /**
     * 以用户为单位，缓存用户所具备的分类权限
     */
    private $_user_allowed_cats = array();
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 获取文章附加分类
     * @param int $post_id 文章ID
     * @param string $fields 分类字段（categories表字段）
     * @return array 返回包含分类信息的二维数组
     */
    public function get($post_id, $fields = null){
        $fields || $fields = self::$default_fields;
        $fields = FieldHelper::parse($fields, null, CategoriesTable::model()->getFields());
        
        $sql = new Sql();
        return $sql->from(array('pc'=>'posts_categories'), '')
            ->joinLeft(array('c'=>'categories'), 'pc.cat_id = c.id', CategoriesTable::model()->formatFields($fields['fields']))
            ->where(array('pc.post_id = ?'=>$post_id))
            ->fetchAll();
    }
    
    /**
     * 批量获取文章附加分类
     * @param array $post_ids 文章ID构成的二维数组
     * @param string $fields 分类字段（categories表字段）
     * @return array 返回以文章ID为key的三维数组
     */
    public function mget($post_ids, $fields = null){
        $fields || $fields = self::$default_fields;
        $fields = FieldHelper::parse($fields, null, CategoriesTable::model()->getFields());
        
        $sql = new Sql();
        $cats = $sql->from(array('pc'=>'posts_categories'), 'post_id')
            ->joinLeft(array('c'=>'categories'), 'pc.cat_id = c.id', CategoriesTable::model()->formatFields($fields['fields']))
            ->where(array('pc.post_id IN (?)'=>$post_ids))
            ->fetchAll();
        $return = array_fill_keys($post_ids, array());
        foreach($cats as $c){
            $p = $c['post_id'];
            unset($c['post_id']);
            $return[$p][] = $c;
        }
        return $return;
    }
    
    /**
     * 获取一个或多个文章对应的附加分类ID
     * @param int|array|string $post_ids 文章ID，或文章ID构成的一维数组或逗号分割的字符串
     * @return array 由分类ID构成的一维数组（可能重复）
     */
    public function getSecondaryCatIds($post_ids){
        if(!$post_ids){
            return array();
        }
        
        if(StringHelper::isInt($post_ids)){
            //单个ID
            return $post = PostsCategoriesTable::model()->fetchCol('cat_id', 'post_id = '.$post_ids);
        }else{
            if(is_string($post_ids)){
                //逗号分割的ID串
                $post_ids = explode(',', $post_ids);
            }
            
            return PostsCategoriesTable::model()->fetchCol('cat_id', array(
                'post_id IN (?)'=>$post_ids,
            ));
        }
    }
    
    /**
     * 获取一个或多个文章对应的主分类ID
     * @param int|array|string $post_ids 文章ID，或文章ID构成的一维数组或逗号分割的字符串
     * @return array 由分类ID构成的一维数组（可能重复）
     */
    public function getPrimaryCatId($post_ids){
        if(!$post_ids){
            return array();
        }
        
        if(StringHelper::isInt($post_ids)){
            //单个ID
            $post = PostsTable::model()->find($post_ids, 'cat_id');
            return array($post['cat_id']);
        }else{
            if(is_string($post_ids)){
                //逗号分割的ID串
                $post_ids = explode(',', $post_ids);
            }
            
            return PostsTable::model()->fetchCol('cat_id', array(
                'id IN (?)'=>$post_ids,
            ));
        }
    }
    
    /**
     * 获取一个或多个文章对应的主附分类ID并集
     * @param int|array|string $post_ids 文章ID，或文章ID构成的一维数组或逗号分割的字符串
     * @return array 由分类ID构成的一维数组（可能重复）
     */
    public function getAllCatIds($post_ids){
        return array_merge(
            $this->getPrimaryCatId($post_ids),
            $this->getSecondaryCatIds($post_ids)
        );
    }
    
    /**
     * 获取用户分类权限
     * 此函数并不检查“文章分类权限控制”开关是否打开
     * @param string $user_id 用户ID，默认为当前登录用户ID
     * @param bool $cache 是否缓存
     * @return array
     */
    public function getAllowedCatIds($user_id = null, $cache = true){
        $user_id || $user_id = \F::app()->current_user;
        
        if($cache && isset($this->_user_allowed_cats[$user_id])){
            return $this->_user_allowed_cats[$user_id];
        }
        
        if(UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN, $user_id)){
            //如果是超级管理员，设置一个*
            return $this->_user_allowed_cats[$user_id] = array('*');
        }
        
        //获取文章根分类（未分类），任何人都可以编辑未分类文章
        $post_root = CategoryService::service()->get('_system_post', 'id');
        
        //获取用户角色ID
        $role_ids = UserRoleService::service()->getIds($user_id);
        
        //获取角色属性和0和
        $allowed_cats = array_merge(
            array('0', $post_root['id']),
            RolesCatsTable::model()->fetchCol('cat_id', 'role_id IN ('.implode(',', $role_ids).')')
        );
        
        return $this->_user_allowed_cats[$user_id] = $allowed_cats;
    }
    
    /**
     * 判断用户是否具备编辑指定分类的权限
     * @param int $cat_id 分类ID
     * @param int $user_id 用户ID，默认为当前登录用户ID
     * @return bool
     */
    public function isAllowedCat($cat_id, $user_id = null){
        $user_id || $user_id = \F::app()->current_user;
        
        if(OptionService::get('system:post_role_cats')){
            //开启了文章分类权限控制，进行验证
            if(UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN, $user_id)){
                //如果是超级管理员，返回true
                return true;
            }
            
            $allowed_cats = $this->getAllowedCatIds($user_id, true);
            
            return in_array('*', $allowed_cats) || in_array($cat_id, $allowed_cats);
        }else{
            //未开启文章分类权限控制，直接返回true
            return true;
        }
    }
    
    /**
     * 文章相关分类文章数递增（包含主分类和附加分类）
     * @param int|array|string $post_ids 文章ID，或文章ID构成的一维数组或逗号分割的字符串
     * @param bool $invert 增量取反（若为true，则递减）
     * @return bool
     */
    public function incr($post_ids, $invert = false){
        if(!$post_ids){
            return false;
        }
        
        $cat_ids = $this->getAllCatIds($post_ids);
        
        $count_map = ArrayHelper::countValues($cat_ids);
        foreach($count_map as $num => $sub_cat_ids){
            CategoriesTable::model()->incr(array(
                'id IN (?)'=>$sub_cat_ids
            ), 'count', $invert ? - $num : $num);
        }
        
        return true;
    }
    
    /**
     * 文章相关分类文章数递减（包含主分类和附加分类）
     * @param int|array|string $post_ids 文章ID，或文章ID构成的一维数组或逗号分割的字符串
     * @return bool
     */
    public function decr($post_ids){
        return $this->incr($post_ids, true);
    }
    
    /**
     * 设置附加分类（创建或编辑文章时调用）
     * @param int $primary_cat_id 主分类ID
     * @param string|array $secondary_cat_ids 逗号分割的分类ID，或由分类ID构成的一维数组。若为空，则删除指定文章的所有附加分类
     * @param int $post_id 文章ID
     * @param int|null $old_status 文章原状态
     * @param int|null $new_status 文章新状态
     * @throws PostErrorException
     */
    public function setSecondaryCats($primary_cat_id, $secondary_cat_ids, $post_id, $old_status, $new_status){
        if($secondary_cat_ids){
            if(!is_array($secondary_cat_ids)){
                $secondary_cat_ids = explode(',', $secondary_cat_ids);
            }
        }else{
            $secondary_cat_ids = array();
        }
        
        //若主分类在附加分类中，则将其从附加分类中移除
        $key = array_search($primary_cat_id, $secondary_cat_ids);
        if($key !== false){
            unset($secondary_cat_ids[$key]);
        }
        
        //验证分类ID是否存在
        if($secondary_cat_ids && count(CategoriesTable::model()->fetchAll(array(
            'id IN (?)'=>$secondary_cat_ids,
        ), 'id')) != count($secondary_cat_ids)){
            //实际存在的分类记录数与输入记录数不相等，意味着有指定分类ID不存在
            throw new PostErrorException('指定附加分类不存在');
        }
        
        $old_cat_ids = array();
        $deleted_cat_ids = array();
        if($old_status !== null){
            //原状态非null，说明是编辑文章，需要获取文章原标签，删掉已经被删掉的标签
            $old_cat_ids = PostsCategoriesTable::model()->fetchCol('cat_id', array(
                'post_id = ?'=>$post_id,
            ));
            
            //删除已被删除的标签
            $deleted_cat_ids = array_diff($old_cat_ids, $secondary_cat_ids);
            //若主分类本来在附加分类中，则将其从附加分类中删除
            if(in_array($primary_cat_id, $old_cat_ids)){
                $deleted_cat_ids[] = $primary_cat_id;
            }
            if($deleted_cat_ids){
                PostsCategoriesTable::model()->delete(array(
                    'post_id = ?'=>$post_id,
                    'cat_id IN (?)'=>$deleted_cat_ids
                ));
            }
        }
        
        //插入新的标签
        if($old_cat_ids){
            $new_cat_ids = array_diff($secondary_cat_ids, $old_cat_ids);
        }else{
            $new_cat_ids = $secondary_cat_ids;
        }
        if($new_cat_ids){
            foreach($new_cat_ids as $v){
                PostsCategoriesTable::model()->insert(array(
                    'post_id'=>$post_id,
                    'cat_id'=>$v,
                ));
            }
        }
        
        if($old_status === null && $new_status == PostsTable::STATUS_PUBLISHED){
            //没有原状态，说明是新增文章，且文章状态为已发布：所有输入分类文章数加一
            CategoryService::service()->incr($secondary_cat_ids);
        }else if($old_status == PostsTable::STATUS_PUBLISHED && $new_status != PostsTable::STATUS_PUBLISHED){
            //本来处于已发布状态，编辑后变成未发布：文章原分类文章数减一
            CategoryService::service()->decr($old_cat_ids);
        }else if($old_status != PostsTable::STATUS_PUBLISHED && $new_status == PostsTable::STATUS_PUBLISHED){
            //本来是未发布状态，编辑后变成已发布：所有输入分类文章数加一
            CategoryService::service()->incr($secondary_cat_ids);
        }else if($old_status == PostsTable::STATUS_PUBLISHED && $new_status == PostsTable::STATUS_PUBLISHED){
            //本来是已发布状态，编辑后还是已发布状态：新增分类文章数加一，被删除分类文章数减一
            if($new_cat_ids){
                CategoryService::service()->incr($new_cat_ids);
            }
            if($deleted_cat_ids){
                CategoryService::service()->decr($deleted_cat_ids);
            }
        }else if($old_status == PostsTable::STATUS_PUBLISHED && $new_status === null){
            //本来是已发布状态，编辑时并未编辑状态：新增分类文章数加一，被删除分类文章数减一
            if($new_cat_ids){
                CategoryService::service()->incr($new_cat_ids);
            }
            if($deleted_cat_ids){
                CategoryService::service()->decr($deleted_cat_ids);
            }
        }
    }
    
    /**
     * 更新文章主分类文章数（创建或编辑文章时调用）
     * @param int|null $old_cat_id 文章原分类
     * @param int|null $new_cat_id 文章新分类
     * @param int|null $old_status 文章原状态
     * @param int|null $new_status 文章新状态
     */
    public function updatePrimaryCatCount($old_cat_id, $new_cat_id, $old_status, $new_status){
        if($old_cat_id === null){
            if($new_cat_id && $new_status == PostsTable::STATUS_PUBLISHED){
                //$old_cat_id为null，说明是新增文章，若主分类非0，且文章状态为已发布，主分类文章数加一
                CategoryService::service()->incr($new_cat_id);
            }
        //从这里开始，以下都是编辑文章的情况
        }else if($old_status == PostsTable::STATUS_PUBLISHED && $new_status != PostsTable::STATUS_PUBLISHED){
            //本来处于已发布状态，编辑后变成未发布：原主分类文章数减一
            CategoryService::service()->decr($old_cat_id);
        }else if($old_status != PostsTable::STATUS_PUBLISHED && $new_status == PostsTable::STATUS_PUBLISHED){
            //本来处于未发布状态，编辑后变成已发布：新主分类文章数加一
            CategoryService::service()->incr($new_cat_id);
        }else if($old_status == PostsTable::STATUS_PUBLISHED &&
            ($new_status == PostsTable::STATUS_PUBLISHED || $new_status === null) &&
            $old_cat_id != $new_cat_id
        ){
            //本来处于已发布状态，且编辑后还是已发布或未编辑状态，且编辑了主分类：原主分类文章数减一，新主分类文章数加一
            CategoryService::service()->decr($old_cat_id);
            CategoryService::service()->incr($new_cat_id);
        }
    }
    
    /**
     * 通过计算获取指定分类下的文章数
     * @param int $cat_id 分类ID
     * @return int
     */
    public function getPostCount($cat_id){
        $sql = new Sql();
        $result = $sql->from(array('p'=>'posts'), 'COUNT(DISTINCT p.id) AS count')
            ->joinLeft(array('pc'=>'posts_categories'), 'pc.post_id = p.id')
            ->orWhere(array(
                'p.cat_id = ?'=>$cat_id,
                'pc.cat_id = ?'=>$cat_id,
            ))
            ->where(PostsTable::getPublishedConditions('p'))
            ->fetchRow();
        
        return $result['count'];
    }
    
    /**
     * 重置分类文章数
     * （目前都是小网站，且只有出错的时候才需要重置，所以不做分批处理）
     */
    public function resetPostCount(){
        //获取所有文章分类ID
        $cats = CategoryService::service()->getChildIds('_system_post');
        
        //先清零
        CategoriesTable::model()->update(array(
            'count'=>0,
        ), array(
            'id IN (?)'=>$cats,
        ));
        
        foreach($cats as $c){
            CategoriesTable::model()->update(array(
                'count'=>$this->getPostCount($c),
            ), 'id = '.$c);
        }
    }
    
    /**
     * 将category信息装配到$posts中
     * @param array $posts 包含文章信息的三维数组，且第三维必须包含cat_id字段
     * @param null|string $fields 字段（categories表字段）
     * @throws PostErrorException
     */
    public function assemblePrimaryCat(&$posts, $fields = null){
        //获取所有分类ID
        $cat_ids = array();
        foreach($posts as $p){
            if(isset($p['post']['cat_id'])){
                $cat_ids[] = $p['post']['cat_id'];
            }else{
                throw new PostErrorException(__CLASS__.'::'.__METHOD__.'()方法$posts参数中，必须包含cat_id项');
            }
        }
        
        $category_map = CategoryService::service()->mget($cat_ids, $fields);
        
        foreach($posts as $k => $p){
            $p['category'] = isset($category_map[$p['post']['cat_id']]) ? $category_map[$p['post']['cat_id']] : array();
            
            $posts[$k] = $p;
        }
    }
    
    /**
     * 将categories信息装配到$posts中
     * @param array $posts 包含文章信息的三维数组
     *   若包含$posts.post.id字段，则以此字段作为文章ID
     *   若不包含$posts.post.id，则以$posts的键作为文章ID
     * @param null|string $fields 字段（categories表字段）
     * @throws PostErrorException
     */
    public function assembleSecondaryCats(&$posts, $fields = null){
        //获取所有文章ID
        $post_ids = array();
        foreach($posts as $k => $p){
            if(isset($p['post']['id'])){
                $post_ids[] = $p['post']['id'];
            }else{
                $post_ids[] = $k;
            }
        }
        
        $categories_map = $this->mget($post_ids, $fields);
        
        foreach($posts as $k => $p){
            if(isset($p['post']['id'])){
                $post_id = $p['post']['id'];
            }else{
                $post_id = $k;
            }
            
            $p['categories'] = $categories_map[$post_id];
            
            $posts[$k] = $p;
        }
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
     *  - user.*系列可指定作者信息，格式参照\cms\services\user\UserService::get()
     *  - categories.*系列可指定附加分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
     *  - category.*系列可指定主分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
     * @param boolean $children 若该参数为true，则返回所有该分类及其子分类所对应的文章
     * @param string $order 排序字段
     * @param mixed $conditions 附加条件
     * @return array
     */
    public function getPosts($cat, $limit = 10, $field = 'id,title,publish_time,thumbnail', $children = false, $order = 'is_top DESC, sort DESC, publish_time DESC', $conditions = null){
        if(is_array($cat)){
            //分类数组
            return $this->getPostsByCatArray($cat, $limit, $field, $children, $order, $conditions);
        }else if(StringHelper::isInt($cat)){
            //分类ID
            return $this->getPostsByCatId($cat, $limit, $field, $children, $order, $conditions);
        }else{
            //分类别名
            return $this->getPostsByCatAlias($cat, $limit, $field, $children, $order, $conditions);
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
     *  - user.*系列可指定作者信息，格式参照\cms\services\user\UserService::get()
     *  - categories.*系列可指定附加分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
     *  - category.*系列可指定主分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
     * @param boolean $children 若该参数为true，则返回所有该分类及其子分类所对应的文章
     * @param string $order 排序字段
     * @param mixed $conditions 附加条件
     * @return array
     */
    private function getPostsByCatAlias($alias, $limit = 10, $fields = 'id,title,publish_time,thumbnail', $children = false, $order = 'is_top DESC, sort DESC, publish_time DESC', $conditions = null){
        $cat = CategoriesTable::model()->fetchRow(array(
            'alias = ?'=>$alias
        ), 'id,left_value,right_value');
        
        if(!$cat){
            //指定分类不存在，直接返回空数组
            return array();
        }else{
            return $this->getPostsByCatArray($cat, $limit, $fields, $children, $order, $conditions);
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
     *  - user.*系列可指定作者信息，格式参照\cms\services\user\UserService::get()
     *  - categories.*系列可指定附加分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
     *  - category.*系列可指定主分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
     * @param boolean $children 若该参数为true，则返回所有该分类及其子分类所对应的文章
     * @param string $order 排序字段
     * @param mixed $conditions 附加条件
     * @return array
     */
    private function getPostsByCatId($cat_id, $limit = 10, $fields = 'id,title,publish_time,thumbnail', $children = false, $order = 'is_top DESC, sort DESC, publish_time DESC', $conditions = null){
        $cat = CategoriesTable::model()->find($cat_id, 'id,left_value,right_value');
        if(!$cat){
            //指定分类不存在，直接返回空数组
            return array();
        }else{
            return $this->getPostsByCatArray($cat, $limit, $fields, $children, $order, $conditions);
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
     *  - user.*系列可指定作者信息，格式参照\cms\services\user\UserService::get()
     *  - categories.*系列可指定附加分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
     *  - category.*系列可指定主分类，可选categories表字段，若有一项为'categories.*'，则返回所有字段
     * @param boolean $children 若该参数为true，则返回所有该分类及其子分类所对应的文章
     * @param string $order 排序字段
     * @param mixed $conditions 附加条件
     * @return array
     */
    private function getPostsByCatArray($cat, $limit = 10, $fields = 'id,title,publish_time,thumbnail', $children = false, $order = 'is_top DESC, sort DESC, publish_time DESC', $conditions = null){
        //解析$fields
        $fields = FieldHelper::parse($fields, 'post', PostService::$public_fields);
        if(empty($fields['post'])){
            //若未指定返回字段，返回所有允许的字段
            $fields['post'] = array(
                'fields'=>PostService::$public_fields['post']
            );
        }
        
        $sql = new Sql();
        $sql->from(array('p'=>'posts'), 'id')
            ->joinLeft(array('pc'=>'posts_categories'), 'p.id = pc.post_id')
            ->joinLeft(array('pm'=>'post_meta'), 'p.id = pm.post_id')
            ->where(PostsTable::getPublishedConditions('p'))
            ->order($order)
            ->group('p.id');
        if($limit){
            $sql->limit($limit);
        }
        if($children){
            $all_cats = CategoriesTable::model()->fetchCol('id', array(
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
        if($conditions){
            $sql->where($conditions);
        }
        
        $posts = $sql->fetchAll();
        if(!$posts){
            return array();
        }
        
        return PostService::service()->mget(ArrayHelper::column($posts, 'id'), $fields);
    }
}