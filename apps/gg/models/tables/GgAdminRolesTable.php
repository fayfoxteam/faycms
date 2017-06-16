<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 后台管理员角色表
 * 
 * @property int $id Id
 * @property int $merchant_id 所属管理员站点（只关联主账号）
 * @property int $website_id 网站ID
 * @property string $name 角色名称
 * @property int $sort Sort
 * @property string $updated_at 更新时间
 * @property string $created_at 创建时间
 * @property string $deleted_at 删除时间
 */
class GgAdminRolesTable extends Table{
    protected $_name = 'gg_admin_roles';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('merchant_id', 'website_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('id', 'sort'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('name'), 'string', array('max'=>32)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'merchant_id'=>'所属管理员站点（只关联主账号）',
            'website_id'=>'网站ID',
            'name'=>'角色名称',
            'sort'=>'Sort',
            'updated_at'=>'更新时间',
            'created_at'=>'创建时间',
            'deleted_at'=>'删除时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'merchant_id'=>'intval',
            'website_id'=>'intval',
            'name'=>'trim',
            'sort'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
            'deleted_at'=>'',
        );
    }
}