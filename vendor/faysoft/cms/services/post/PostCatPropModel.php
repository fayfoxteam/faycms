<?php
namespace cms\services\post;

use cms\models\tables\PostPropIntTable;
use cms\models\tables\PostPropTextTable;
use cms\models\tables\PostPropVarcharTable;
use cms\models\tables\PostsTable;
use cms\models\tables\PropsTable;
use cms\services\CategoryService;
use cms\services\prop\PropUsageInterface;
use fay\core\db\Table;
use fay\core\ErrorException;

class PostCatPropModel implements PropUsageInterface{
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
     * @return
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
}