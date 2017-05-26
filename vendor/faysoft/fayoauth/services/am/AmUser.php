<?php
namespace fayoauth\services\am;

use fayoauth\services\UserAbstract;

class AmUser extends UserAbstract{
    /**
     * 获取用户昵称。
     * 若第三方字段名特殊，可在子类中重写此方法。
     * @return string
     */
    public function getNickName(){
        return $this->getParam('Name');
    }

    /**
     * 爱名用户Name字段经常是空的，需要生成一个昵称
     * @param string $nickname
     */
    public function setNickName($nickname){
        $this->params['Name'] = $nickname;
    }

    /**
     * 获取openId。
     * 若第三方字段名特殊，可在子类中重写此方法。
     * @return string
     */
    public function getOpenId(){
        return $this->getParam('UserId');
    }

    /**
     * 爱名登录没有这个值
     * @return string
     */
    public function getUnionId(){
        return '';
    }

    /**
     * 获取用户头像。
     * 若第三方字段名特殊，可在子类中重写此方法。
     * @return string
     */
    public function getAvatar(){
        return '';
    }
}