<?php
namespace valentine\models\tables;

use fay\core\db\Table;

/**
 * 用户配对表
 *
 * @property int $id Id
 * @property string $name 组队名称
 * @property int $type 类型
 * @property int $create_time 配对时间
 * @property int $photo 合影图片文件
 * @property string $blessing 对公司的祝福
 * @property int $votes 得票数
 */
class ValentineUserTeamsTable extends Table{
    /**
     * 类型 - 最牛组合名
     */
    const TYPE_COUPLE = 1;
    
    /**
     * 类型 - 最佳创意照
     */
    const TYPE_ORIGINALITY = 2;
    
    /**
     * 类型 - 最美祝福语
     */
    const TYPE_BLESSING = 3;
    
    protected $_name = 'valentine_user_teams';
    
    /**
     * @param string $class_name
     * @return ValentineUserTeamsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('photo'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('votes'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('type'), 'int', array('min'=>0, 'max'=>255)),
            array(array('name'), 'string', array('max'=>50)),
            array(array('photo_server_id'), 'string', array('max'=>100)),
            array(array('blessing'), 'string', array('max'=>255)),
            
            array(array('name', 'photo_server_id', 'blessing', 'type'), 'required'),
        );
    }
    
    public function labels(){
        return array(
            'id'=>'Id',
            'name'=>'组队名称',
            'type'=>'类型',
            'create_time'=>'配对时间',
            'photo'=>'合影图片文件',
            'photo_server_id'=>'微信服务器媒体ID',
            'blessing'=>'对公司的祝福',
            'votes'=>'得票数',
        );
    }
    
    public function filters(){
        return array(
            'id'=>'intval',
            'name'=>'trim',
            'type'=>'intval',
            'photo'=>'intval',
            'photo_server_id'=>'trim',
            'blessing'=>'trim',
        );
    }
}