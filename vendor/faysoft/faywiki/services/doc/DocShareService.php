<?php
namespace faywiki\services\doc;

use cms\services\user\UserService;
use fay\core\Loader;
use fay\core\Service;
use faywiki\models\tables\WikiDocMetaTable;
use faywiki\models\tables\WikiDocSharesTable;

class DocShareService extends Service{
    /**
     * 文档被分享后事件
     */
    const EVENT_SHARED = 'after_doc_shared';
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }

    /**
     * 给文档点赞
     * @param int $doc_id 文档ID
     * @param string $type 分享类型（微博，微信，QQ空间等）
     * @param string $trackid
     * @param int $user_id 用户ID，默认为当前登录用户
     * @param bool|int $sockpuppet 马甲信息
     * @throws DocErrorException
     * @throws DocException
     */
    public static function add($doc_id, $type, $trackid = '', $user_id = null, $sockpuppet = 0){
        //不登陆也可以分享
        $user_id = UserService::makeUserID($user_id, false);

        if(!DocService::isDocIdExist($doc_id)){
            throw new DocErrorException("指定文档ID[{$doc_id}]不存在", 'the-given-doc-id-is-not-exist');
        }

        WikiDocSharesTable::model()->insert(array(
            'doc_id'=>$doc_id,
            'user_id'=>$user_id,
            'type'=>$type,
            'trackid'=>$trackid,
            'sockpuppet'=>$sockpuppet,
            'create_time'=>\F::app()->current_time,
            'ip_int'=>\F::app()->ip_int,
        ));

        //文档点赞数+1
        if($sockpuppet){
            //非真实用户行为
            WikiDocMetaTable::model()->incr($doc_id, array('shares'), 1);
        }else{
            //真实用户行为
            WikiDocMetaTable::model()->incr($doc_id, array('shares', 'real_shares'), 1);
        }

        //触发事件
        \F::event()->trigger(self::EVENT_SHARED, $doc_id);
    }
}