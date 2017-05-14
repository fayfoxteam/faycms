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
}