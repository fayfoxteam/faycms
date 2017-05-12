<?php
namespace cms\services\post;

use cms\models\tables\UserPropIntTable;
use cms\models\tables\UserPropTextTable;
use cms\models\tables\UserPropVarcharTable;
use cms\services\prop\PropTypeInterface;
use fay\core\db\Table;
use fay\core\ErrorException;

class UserRolePropModel implements PropTypeInterface{
    /**
     * 获取显示名
     * @return string
     */
    public function getTypeName(){
        return '角色属性';
    }

    /**
     * 根据文章分类ID，获取其所有父节点ID
     * @param int $refer
     * @return array
     */
    public function getRelationRefers($refer){
        return array();
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
                return UserPropIntTable::model();
                break;
            case 'varchar':
                return UserPropVarcharTable::model();
                break;
            case 'text':
                return UserPropTextTable::model();
            default:
                throw new ErrorException("不支持的数据类型[{$data_type}]");
        }
    }

    /**
     * 获取一个用户相关的属性
     * @param int $id 用户ID
     * @return array
     */
    public function getItemProps($id){
        //@todo
        return array();
    }
}