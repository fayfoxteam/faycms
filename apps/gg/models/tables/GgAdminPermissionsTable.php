<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Gg admin permissions table model
 * 
 * @property int $id Id
 * @property int $parent_id Parent Id
 * @property int $node_bid Node Bid
 * @property int $node_sid Node Sid
 * @property int $level Level
 * @property string $name Name
 * @property string $controller Controller
 * @property string $action Action
 * @property string $redirect_uri Redirect Uri
 * @property string $remark Remark
 * @property string $icon Icon
 * @property int $sort Sort
 * @property int $status Status
 * @property int $is_display 0为不显示，1为显示在菜单中
 * @property string $updated_at Updated At
 * @property string $created_at Created At
 * @property string $deleted_at Deleted At
 */
class GgAdminPermissionsTable extends Table{
    protected $_name = 'gg_admin_permissions';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('sort'), 'int', array('min'=>-32768, 'max'=>32767)),
            array(array('id', 'parent_id', 'node_bid', 'node_sid'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('level'), 'int', array('min'=>0, 'max'=>255)),
            array(array('name', 'controller', 'action', 'icon'), 'string', array('max'=>32)),
            array(array('redirect_uri', 'remark'), 'string', array('max'=>255)),
            array(array('is_display'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'parent_id'=>'Parent Id',
            'node_bid'=>'Node Bid',
            'node_sid'=>'Node Sid',
            'level'=>'Level',
            'name'=>'Name',
            'controller'=>'Controller',
            'action'=>'Action',
            'redirect_uri'=>'Redirect Uri',
            'remark'=>'Remark',
            'icon'=>'Icon',
            'sort'=>'Sort',
            'status'=>'Status',
            'is_display'=>'0为不显示，1为显示在菜单中',
            'updated_at'=>'Updated At',
            'created_at'=>'Created At',
            'deleted_at'=>'Deleted At',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'parent_id'=>'intval',
            'node_bid'=>'intval',
            'node_sid'=>'intval',
            'level'=>'intval',
            'name'=>'trim',
            'controller'=>'trim',
            'action'=>'trim',
            'redirect_uri'=>'trim',
            'remark'=>'trim',
            'icon'=>'trim',
            'sort'=>'intval',
            'status'=>'intval',
            'is_display'=>'intval',
            'updated_at'=>'',
            'created_at'=>'',
            'deleted_at'=>'',
        );
    }
}