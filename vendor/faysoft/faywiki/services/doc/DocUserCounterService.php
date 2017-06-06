<?php
namespace faywiki\services\doc;

use fay\core\Loader;
use fay\core\Service;
use fay\core\Sql;
use cms\services\user\UserCounterService;
use cms\models\tables\UserCounterTable;
use faywiki\models\tables\WikiDocsTable;

class DocUserCounterService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 递增一个或多个指定用户的计数
     * @param array|int $user_ids
     * @param int $value 增量，默认为1，正数表示递减
     */
    public function incr($user_ids, $value = 1){
        UserCounterService::service()->incr($user_ids, 'wiki_docs', $value);
    }
    
    /**
     * 递减一个或多个指定用户的计数
     * @param array|int $user_ids
     * @param int $value 增量，默认为1，正数表示递减
     * @return int
     */
    public function decr($user_ids, $value = 1){
        return $this->incr($user_ids, - $value);
    }
    
    /**
     * 通过计算获取指定用户的文档数
     * @param int $user_id 用户ID
     * @return int
     */
    public function getCount($user_id){
        $sql = new Sql();
        $result = $sql->from(array('d'=>WikiDocsTable::model()->getTableName()), 'COUNT(*)')
            ->where('d.user_id = ?', $user_id)
            ->where(WikiDocsTable::getPublishedConditions('d'))
            ->fetchRow();
        return $result['COUNT(*)'];
    }
    
    /**
     * 重置用户文档数
     * （目前都是小网站，且只有出错的时候才需要重置，所以不做分批处理）
     */
    public function resetCount(){
        $sql = new Sql();
        $results = $result = $sql->from(array('d'=>WikiDocsTable::model()->getTableName()), array('user_id', 'COUNT(*) AS count'))
            ->where(WikiDocsTable::getPublishedConditions('d'))
            ->group('p.user_id')
            ->fetchAll();
        
        //先清零
        UserCounterTable::model()->update(array(
            'wiki_docs'=>0
        ), false);
        
        foreach($results as $r){
            UserCounterTable::model()->update(array(
                'wiki_docs'=>$r['count']
            ), $r['user_id']);
        }
    }
}