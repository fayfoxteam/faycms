<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Analyst Macs model
 *
 * @property int $id Id
 * @property string $user_agent User Agent
 * @property string $browser 浏览器内核
 * @property string $browser_version 内核版本
 * @property string $shell 浏览器套壳
 * @property string $shell_version 套壳版本
 * @property string $os 操作系统
 * @property int $ip_int Ip Int
 * @property int $screen_width 屏幕宽度
 * @property int $screen_height 屏幕高度
 * @property string $url Url
 * @property string $refer 来源url
 * @property string $se Se
 * @property string $keywords Keywords
 * @property string $fmac FMac
 * @property int $create_time 创建时间
 * @property string $create_date 创建日期
 * @property int $hour Hour
 * @property string $trackid Trackid
 * @property int $site Site
 */
class AnalystMacsTable extends Table{
    protected $_name = 'analyst_macs';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id', 'create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('screen_width', 'screen_height', 'site'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('hour'), 'int', array('min'=>0, 'max'=>255)),
            array(array('user_agent', 'url', 'refer'), 'string', array('max'=>255)),
            array(array('browser', 'browser_version', 'shell', 'shell_version', 'os', 'trackid'), 'string', array('max'=>30)),
            array(array('se'), 'string', array('max'=>10)),
            array(array('keywords'), 'string', array('max'=>50)),
            array(array('fmac'), 'string', array('max'=>36)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'user_agent'=>'User Agent',
            'browser'=>'浏览器内核',
            'browser_version'=>'内核版本',
            'shell'=>'浏览器套壳',
            'shell_version'=>'套壳版本',
            'os'=>'操作系统',
            'ip_int'=>'IP',
            'screen_width'=>'屏幕宽度',
            'screen_height'=>'屏幕高度',
            'url'=>'Url',
            'refer'=>'来源url',
            'se'=>'Se',
            'keywords'=>'Keywords',
            'fmac'=>'FMac',
            'create_time'=>'创建时间',
            'create_date'=>'创建日期',
            'hour'=>'Hour',
            'trackid'=>'Trackid',
            'site'=>'Site',
        );
    }

    public function filters(){
        return array(
            'user_agent'=>'trim',
            'browser'=>'trim',
            'browser_version'=>'trim',
            'shell'=>'trim',
            'shell_version'=>'trim',
            'os'=>'trim',
            'screen_width'=>'intval',
            'screen_height'=>'intval',
            'url'=>'trim',
            'refer'=>'trim',
            'se'=>'trim',
            'keywords'=>'trim',
            'fmac'=>'trim',
            'create_date'=>'',
            'hour'=>'intval',
            'trackid'=>'trim',
            'site'=>'intval',
        );
    }
}