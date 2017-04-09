<?php
namespace fay\services\tag;

use fay\core\Service;
use fay\helpers\FieldHelper;
use fay\models\tables\TagCounterTable;

class TagCounterService extends Service{
    /**
     * 可返回字段
     */
    public static $public_fields = array(
        'posts', 'feeds'
    );
    
    /**
     * @param string $class_name
     * @return TagCounterService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    /**
     * 递增一个或多个指定标签的计数
     * @param array|int $tag_ids
     * @param string $field tag_counter表对应的列名
     * @param int $value 增量，默认为1，可以是负数
     * @return int
     */
    public function incr($tag_ids, $field, $value = 1){
        if(!$tag_ids){
            return 0;
        }
        if(!is_array($tag_ids)){
            $tag_ids = explode(',', $tag_ids);
        }
        
        return TagCounterTable::model()->incr(array(
            'tag_id IN (?)'=>$tag_ids,
        ), $field, $value);
    }
    
    /**
     * 递减一个或多个指定标签的计数
     * @param array|int $tag_ids
     * @param string $field tag_counter表对应的列名
     * @param int $value 增量，默认为1，正数表示递减
     * @return int
     */
    public function decr($tag_ids, $field, $value = 1){
        return $this->incr($tag_ids, $field, -$value);
    }
    
    /**
     * 获取标签信息
     * @param int $tag_id 标签ID
     * @param string $fields
     * @return array 返回包含标签profile信息的二维数组
     */
    public function get($tag_id, $fields = null){
        //若传入$fields为空，则返回默认字段
        $fields || $fields = self::$public_fields;
        
        //格式化fields
        $fields = FieldHelper::parse($fields, null, self::$public_fields);
        
        return TagCounterTable::model()->fetchRow(array(
            'tag_id = ?'=>$tag_id,
        ), $fields['fields']);
    }
    
    /**
     * 批量获取标签信息
     * @param array $tag_ids 标签ID一维数组
     * @param string $fields
     * @return array 返回以标签ID为key的三维数组
     */
    public function mget($tag_ids, $fields = null){
        //若传入$fields为空，则返回默认字段
        $fields || $fields = self::$public_fields;
        
        //格式化fields
        $fields = FieldHelper::parse($fields, null, self::$public_fields);
        
        if(!in_array('tag_id', $fields['fields'])){
            $fields['fields'][] = 'tag_id';
            $remove_tag_id = true;
        }else{
            $remove_tag_id = false;
        }
        $counters = TagCounterTable::model()->fetchAll(array(
            'tag_id IN (?)'=>$tag_ids,
        ), $fields['fields'], 'tag_id');
        $return = array_fill_keys($tag_ids, array());
        foreach($counters as $c){
            $u = $c['tag_id'];
            if($remove_tag_id){
                unset($c['tag_id']);
            }
            $return[$u] = $c;
        }
        return $return;
    }
}