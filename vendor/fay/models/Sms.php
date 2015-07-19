<?php
namespace fay\models;

use fay\core\Model;
use fay\core\Loader;
use fay\core\ErrorException;

class Sms extends Model
{
    private static $_instance;
    private static $ucpass;

    public static function getInstance()
    {
        if (!\F::config()->get('send_sms')) {
            return;
        }
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
            //获取配置项
            $options = Option::getTeam('ucpaas');

            if (empty($options['accountsid']) || empty($options['token']) || empty($options['appid'])) {
                throw new ErrorException('云之讯参数未配置正确');
            }

            Loader::vendor('Ucpaas/Ucpaas.class');


            $options = Option::getTeam('ucpaas');
            //初始化 $options必填 实例化短信类
            self::$ucpass = new \Ucpaas($options);
        }
        return self::$_instance;
    }



    /**
     * 获取开发者信息
     *
     */
    public function getDevinfo(){
        //开发者账号信息查询默认为json或xml
        dump( self::$ucpass->getDevinfo('json'));
    }


    /**
     * 申请client账号
     *
     * $appId = "xxxx";
     * $clientType = "0";
     * $charge = "0";
     * $friendlyName = '';
     * $mobile = "18612345678";
     */

    public function applyClient($appId, $clientType, $charge, $friendlyName, $mobile){
        return self::$ucpass->applyClient($appId, $clientType, $charge, $friendlyName, $mobile);
    }

    /*
    * 删除client账号
    * $appId = "xxxx";
    * $clientNumber='xxxxx';
     *
     */
    public function releaseClient($clientNumber,$appId){
        return self::$ucpass->releaseClient($clientNumber,$appId);
    }


    /*获取Client账号
    * $appId = "xxxx";
    * $start = "0";
    * $limit = "100";
    */
    public function getClientList($appId,$start,$limit){
        return self::$ucpass->getClientList($appId,$start,$limit);
    }

    /*以Client账号方式查询Client信息
    * $appId = "xxxx";
    * $clientNumber='xxxx';
    */
    public function getClientInfo($appId,$clientNumber){
        return self::$ucpass->getClientInfo($appId,$clientNumber);
    }


    /*
     * 以手机号码方式查询Client信息
     * $appId = "xxxx";
     * $mobile = "18612345678";
     *
     */
    public function getClientInfoByMobile($appId,$mobile){
        return self:: $ucpass->getClientInfoByMobile($appId,$mobile);
    }


    /*
     * 应用话单下载,通过HTTPS POST方式提交请求，云之讯融合通讯开放平台收到请求后，返回应用话单下载地址及文件下载检验码。
     * day 代表前一天的数据（从00:00 – 23:59）；week代表前一周的数据(周一 到周日)；month表示上一个月的数据（上个月表示当前月减1，如果今天是4月10号，则查询结果是3月份的数据）
     * $appId = "xxxx";
     * $date = "day";
     *
     */
    public function getBillList($appId,$date){
        return self::$ucpass->getBillList($appId,$date);
    }

    /*
     * Client充值,通过HTTPS POST方式提交充值请求，云之讯融合通讯开放平台收到请求后，返回Client充值结果。
    * $appId = "xxxx";
    * $clientNumber='xxxx';
    * $clientType = "0";
    * $charge = "0";
     *
     */

    public function chargeClient($appId,$clientNumber,$clientType,$charge){
        return self::$ucpass->chargeClient($appId,$clientNumber,$clientType,$charge);
    }



    /*双向回拨,云之讯融合通讯开放平台收到请求后，将向两个电话终端发起呼叫，双方接通电话后进行通话。
    * $appId = "xxxx";
    * $fromClient = "xxxx";
    * $to = "18612345678";
    * $fromSerNum = '';
    * $toSerNum = '';
     *
     */
    public function callBack($appId,$fromClient,$to){
        return self::$ucpass->callBack($appId,$fromClient,$to);
    }

    /*语音验证码,云之讯融合通讯开放平台收到请求后，向对象电话终端发起呼叫，接通电话后将播放指定语音验证码序列
    * $appId = "xxxx";
    * $verifyCode = "1256";
    * $to = "18612345678";
    *
    */
    public function voiceCode($appId,$verifyCode,$to){
        return self::$ucpass->voiceCode($appId,$verifyCode,$to);
    }



    /*
     * 短信验证码（模板短信）,
     * 默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），
     * 超过长度短信平台将会自动分割为多条发送。
     * 分割后的多条短信将按照具体占用条数计费。
     * $appId = "xxxx";
     * $to = "18612345678";
     * $templateId = "1";
     * $param="test,1256,3";
    */

    public function templateSMS($appId,$to,$templateId,$param){
        return self::$ucpass->templateSMS($appId,$to,$templateId,$param);
    }
}