<?php
namespace cms\services\post;

use cms\models\tables\PostPropIntTable;
use cms\models\tables\PostPropTextTable;
use cms\models\tables\PostPropVarcharTable;
use cms\models\tables\PostsTable;
use cms\models\tables\PropsTable;
use cms\services\CategoryService;
use cms\services\prop\ItemPropService;
use cms\services\prop\PropService;
use cms\services\prop\PropUsageInterface;
use fay\core\db\Table;
use fay\core\ErrorException;
use fay\core\Loader;
use fay\core\Service;

class PostPropService extends Service implements PropUsageInterface{
    /**
     * @param string $class_name
     * @return $this
     */
    public static function service($class_name = __CLASS__){
        return Loader::singleton($class_name);
    }

    /**
     * 获取用途显示名
     * @return string
     */
    public function getUsageName(){
        return '文章分类属性';
    }

    /**
     * 获取用途编号
     * @return int
     */
    public function getUsageType(){
        return PropsTable::USAGE_POST_CAT;
    }

    /**
     * 根据文章ID，获取属性用途（实际上就是主分类）
     * @param int $post_id
     * @return array|int
     * @throws ErrorException
     */
    public function getUsages($post_id){
        $post = PostsTable::model()->find($post_id, 'cat_id');
        if(!$post){
            throw new ErrorException("指定文章ID[{$post_id}]不存在");
        }

        return $post['cat_id'];
    }

    /**
     * 根据主用途，获取关联用途（实际上就是根据主分类，获取其父节点）
     * @param int $cat_id
     * @return array
     */
    public function getSharedUsages($cat_id){
        return CategoryService::service()->getParentIds($cat_id, '_system_post', false);
    }

    /**
     * 根据数据类型，获取相关表model
     * @param string $data_type
     * @return Table
     * @throws ErrorException
     */
    public function getModel($data_type){
        switch($data_type){
            case 'int':
                return PostPropIntTable::model();
                break;
            case 'varchar':
                return PostPropVarcharTable::model();
                break;
            case 'text':
                return PostPropTextTable::model();
            default:
                throw new ErrorException("不支持的数据类型[{$data_type}]");
        }
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
            $props = PropService::service()->mget($fields, PropsTable::USAGE_POST_CAT);
        }

        foreach($posts as $k => $p){
            if(isset($p['post']['id'])){
                $post_id = $p['post']['id'];
            }else{
                $post_id = $k;
            }

            $item_prop = new ItemPropService($post_id, $this);
            $p['props'] = $item_prop->getPropSet($props);

            $posts[$k] = $p;
        }
    }

    /**
     * 根据分类id，获取所有相关属性
     * @param int $cat_id
     * @return array
     */
    public function getPropsByCatId($cat_id){
        $parents = CategoryService::service()->getParentIds($cat_id, '_system_post', false);
        
        return PropService::service()->getPropsByUsage($cat_id, PropsTable::USAGE_POST_CAT, $parents);
    }

    /**
     * 根据文章id，获取所有相关属性
     * @param int $post_id
     * @return array
     * @throws ErrorException
     */
    public function getPropsByPostId($post_id){
        $post = PostsTable::model()->find($post_id, 'cat_id');
        if(!$post){
            throw new ErrorException("指定文章ID[{$post_id}]不存在");
        }
        
        return $this->getPropsByCatId($post['cat_id']);
    }

    /**
     * 获取指定文章的属性集
     * @param int $post_id
     * @param null|array $props
     * @return array
     */
    public function getPropSet($post_id, $props = null){
        return $this->getItemProp($post_id)->getPropSet($props);
    }

    /**
     * 创建属性集
     * @param int $post_id
     * @param array $data
     * @param null|array $props 若指定$props则只创建指定的属性，否则根据文章id，创建全部属性
     */
    public function createPropSet($post_id, $data, $props = null){
        if($props === null){
            $props = $this->getPropsByPostId($post_id);
        }
        $this->getItemProp($post_id)->createPropSet($props, $data);
    }

    /**
     * 更新属性集
     * @param int $post_id
     * @param array $data
     * @param null|array $props 若指定$props则只更新指定的属性，否则根据文章id，更新全部属性
     */
    public function updatePropSet($post_id, $data, $props = null){
        if($props === null){
            $props = PostPropService::service()->getPropsByPostId($post_id);
        }
        $this->getItemProp($post_id)->updatePropSet($props, $data);
    }

    /**
     * 获取文章属性类实例
     * @param int $post_id
     * @return ItemPropService
     */
    protected function getItemProp($post_id){
        return new ItemPropService($post_id, $this);
    }
}