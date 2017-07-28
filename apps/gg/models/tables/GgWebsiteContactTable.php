<?php
namespace gg\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 站点联系方式相关信息
 * 
 * @property int $website_id Website Id
 * @property string $email Email
 * @property string $qq Qq
 * @property int $qrcode Qrcode
 * @property string $weibo 微博名
 * @property string $weibo_url 微博网址
 * @property string $weixin 微信
 * @property string $address 地址
 * @property string $phone 电话
 */
class GgWebsiteContactTable extends Table{
    protected $_name = 'gg_website_contact';
    protected $_primary = 'website_id';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('qrcode'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('website_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('qq'), 'string', array('max'=>20)),
            array(array('weibo', 'weibo_url', 'weixin'), 'string', array('max'=>50)),
            array(array('address'), 'string', array('max'=>255)),
            array(array('phone'), 'string', array('max'=>30)),
        );
    }

    public function labels(){
        return array(
            'website_id'=>'Website Id',
            'email'=>'Email',
            'qq'=>'Qq',
            'qrcode'=>'Qrcode',
            'weibo'=>'微博名',
            'weibo_url'=>'微博网址',
            'weixin'=>'微信',
            'address'=>'地址',
            'phone'=>'电话',
        );
    }

    public function filters(){
        return array(
            'website_id'=>'intval',
            'email'=>'trim',
            'qq'=>'trim',
            'qrcode'=>'intval',
            'weibo'=>'trim',
            'weibo_url'=>'trim',
            'weixin'=>'trim',
            'address'=>'trim',
            'phone'=>'trim',
        );
    }
}