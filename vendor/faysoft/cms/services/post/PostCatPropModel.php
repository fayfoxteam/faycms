<?php
namespace cms\services\post;

use cms\models\tables\PostPropIntTable;
use cms\models\tables\PostPropTextTable;
use cms\models\tables\PostPropVarcharTable;
use cms\services\CategoryService;
use cms\services\prop\PropTypeInterface;
use fay\core\db\Table;
use fay\core\ErrorException;

class PostCatPropModel implements PropTypeInterface{
    /**
     * 获取显示名
     * @return string
     */
    public function getTypeName(){
        return '文章分类属性';
    }

    /**
     * 根据文章分类ID，获取其所有父节点ID
     * @param int $refer
     * @return array
     */
    public function getRelationRefers($refer){
        return CategoryService::service()->getParentIds($refer, '_system_post', false);
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
     * 获取一篇文章相关的属性
     * @param int $id 文章ID
     * @return array
     */
    public function getItemProps($id){
        //@todo 
        return array();
    }
}