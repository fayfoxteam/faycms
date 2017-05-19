<?php
namespace cms\services\user;

use cms\models\tables\PropsTable;
use cms\models\tables\RolesTable;
use cms\models\tables\UserPropIntTable;
use cms\models\tables\UserPropTextTable;
use cms\models\tables\UserPropVarcharTable;
use cms\models\tables\UsersRolesTable;
use cms\services\prop\ItemPropService;
use cms\services\prop\PropService;
use cms\services\prop\PropUsageInterface;
use fay\core\db\Table;
use fay\core\ErrorException;
use fay\core\Service;
use fay\core\Loader;

class  UserPropService extends Service implements PropUsageInterface{
    /**
     * @param string $class_name
     * @return UserPropService
     */
    public static function service($class_name = __CLASS__){
        return Loader::singleton($class_name);
    }

    /**
     * 获取用途显示名
     * @return string
     */
    public function getUsageName(){
        return '用户角色属性';
    }

    /**
     * 获取用途类型编号
     * @return int
     */
    public function getUsageType(){
        return PropsTable::USAGE_ROLE;
    }

    /**
     * 获取用途具体记录的标题。
     * 例如：用途是文章分类属性，则根据分类Id，获取分类标题
     * @param int $id
     * @return string
     * @throws ErrorException
     */
    public function getUsageItemTitle($id){
        $role = RolesTable::model()->find($id, 'title');
        if(!$role){
            throw new ErrorException("指定角色ID[{$id}]不存在");
        }
        return $role['title'];
    }

    /**
     * 根据用户ID，获取用户角色
     * @param int $user_id 用户ID
     * @return array
     */
    public function getUsages($user_id){
        return UsersRolesTable::model()->fetchCol('role_id', array(
            'user_id = ?'=>$user_id,
        ));
    }

    /**
     * 角色是没有关联角色这样的概念的，所以直接返回空数组
     * @param array $role_id 角色ID
     * @return array
     */
    public function getSharedUsages($role_id){
        //角色是没有关联角色这样的概念的
        return array();
    }

    /**
     * 根据数据类型，获取相关表model
     * @param string $data_type 至少需要实现int，varchar，text类型。
     *  此类表必须包含3个字段：refer, prop_id, content
     *  其中content字段类型分别为：int(10), varchar(255), text
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
     * 根据给定角色id，返回相关属性
     * @param array $role_ids
     * @return array
     */
    public function getPropsByRoleIds(array $role_ids){
        return PropService::service()->getPropsByUsage($role_ids, PropsTable::USAGE_ROLE);
    }
    
    /**
     * 根据用户ID，获取用户属性
     * @param int $user_id
     * @return array
     */
    public function getPropsByUserId($user_id){
        $role_ids = UserRoleService::service()->getIds($user_id);
        if(!$role_ids){
            return array();
        }
        
        return $this->getPropsByRoleIds($role_ids);
    }
    
    /**
     * 新增一个用户属性集
     * @param int $user_id 用户ID
     * @param array $data 以属性ID为键的属性键值数组
     * @param null|array $props 属性。若为null，则根据用户ID获取属性
     */
    public function createPropSet($user_id, $data, $props = null){
        if($props === null){
            $props = $this->getPropsByUserId($user_id);
        }
        $this->getItemProp($user_id)->createPropSet($props, $data);
    }
    
    /**
     * 更新一个用户属性集
     * @param int $user_id 用户ID
     * @param array $data 以属性ID为键的属性键值数组
     * @param null|array $props 属性。若为null，则根据用户ID获取属性
     */
    public function updatePropSet($user_id, $data, $props = null){
        if($props === null){
            $props = $this->getPropsByUserId($user_id);
        }
        $this->getItemProp($user_id)->updatePropSet($props, $data);
    }
    
    /**
     * @see \fay\models\PropModel::getPropertySet()
     * @param int $user_id 文章ID
     * @param null|array $props 属性列表
     * @return array
     */
    public function getPropSet($user_id, $props = null){
        return $this->getItemProp($user_id)->getPropSet($props);
    }

    /**
     * 获取文章属性类实例
     * @param int $user_id
     * @return ItemPropService
     */
    protected function getItemProp($user_id){
        return new ItemPropService($user_id, $this);
    }
}