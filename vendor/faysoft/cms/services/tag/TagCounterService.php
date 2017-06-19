<?php
namespace cms\services\tag;

use cms\models\tables\TagCounterTable;
use fay\core\Loader;
use fay\core\Service;
use fay\helpers\FieldsHelper;

class TagCounterService extends Service{
    /**
     * 可返回字段
     */
    public static $default_fields = array(
        'posts'
    );
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
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
        $fields = new FieldsHelper($fields ? $fields : self::$default_fields, 'tag_counter');
        
        return TagCounterTable::model()->fetchRow(array(
            'tag_id = ?'=>$tag_id,
        ), $fields->getFields());
    }
    
    /**
     * 批量获取标签信息
     * @param array $tag_ids 标签ID一维数组
     * @param string $fields
     * @return array 返回以标签ID为key的三维数组
     */
    public function mget($tag_ids, $fields = null){
        //格式化fields
        $fields = new FieldsHelper($fields ? $fields : self::$default_fields, 'tag_counter');
        
        if(!$fields->hasField('tag_id')){
            $fields->addFields('tag_id');
            $remove_tag_id = true;
        }else{
            $remove_tag_id = false;
        }
        $counters = TagCounterTable::model()->fetchAll(array(
            'tag_id IN (?)'=>$tag_ids,
        ), $fields->getFields(), 'tag_id');
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