<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 后台节点 与 权限表关联（多对多）
 * 
 * @property int $id Id
 * @property int $role_id 节点id
 * @property int $per_id 权限ID
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 */
class GgAdminRolePerTable extends Table{
    protected $_name = 'gg_admin_role_per';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('role_id', 'per_id'), 'int', array('min'=>0, 'max'=>65535)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'role_id'=>'节点id',
            'per_id'=>'权限ID',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'role_id'=>'intval',
            'per_id'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
        );
    }
}