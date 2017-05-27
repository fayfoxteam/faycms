<?php
namespace cms\services;

use fay\core\Loader;
use fay\core\Service;
use fay\helpers\ArrayHelper;
use fay\helpers\FieldHelper;
use cms\models\tables\TagsTable;
use cms\models\tables\TagCounterTable;
use fay\core\Sql;
use fay\common\ListView;
use cms\services\tag\TagCounterService;

/**
 * 标签服务
 */
class TagService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 获取标签列表
     * @param string $order 排序方式（例如t.sort这样完整的带表别名前缀的字段）
     * @param int $page_size
     * @param int $page
     * @return array
     */
    public function getList($order, $page_size = 20, $page = 1){
        $sql = new Sql();
        $sql->from(array('t'=>'tags'), 'id,title')
            ->joinLeft(array('tc'=>'tag_counter'), 't.id = tc.tag_id', TagCounterTable::model()->getFields(array('tag_id')))
            ->where('t.status = ' . TagsTable::STATUS_ENABLED)
            ->order($order);
        ;
        $listview = new ListView($sql, array(
            'page_size'=>$page_size,
            'current_page'=>$page,
        ));
        
        return array(
            'tags'=>$listview->getData(),
            'pager'=>$listview->getPager(),
        );
    }
    
    /**
     * 获取指定数量的标签
     * @param string|array $fields
     * @param int $limit
     * @param string $order
     * @return array
     */
    public function getLimit($fields, $limit = 10, $order = 'sort'){
        $fields = FieldHelper::parse($fields, 'tag');
        
        $sql = new Sql();
        $tags = $sql->from(array('t'=>'tags'), 'id')
            ->joinLeft(array('tc'=>'tag_counter'), 't.id = tc.tag_id')
            ->order($order)
            ->limit($limit)
            ->fetchAll()
        ;
        
        return array_values($this->mget(ArrayHelper::column($tags, 'id'), $fields));
    }
    
    public function mget($ids, $fields){
        if(empty($ids)){
            return array();
        }
        
        //解析$ids
        is_array($ids) || $ids = explode(',', $ids);
        
        //解析$fields
        $fields = FieldHelper::parse($fields, 'tag', array(
            'tag'=>TagsTable::model()->getFields(),
            'counter'=>TagCounterTable::model()->getFields(),
        ));
        if(!empty($fields['tag']) && in_array('*', $fields['tag'])){
            //若存在*，视为全字段搜索
            $fields['tag'] = array(
                'fields'=>TagsTable::model()->getFields()
            );
        }
        
        $remove_id_field = false;
        if(empty($fields['tag']['fields']) || !in_array('id', $fields['tag']['fields'])){
            //id总是需要先搜出来的，返回的时候要作为索引
            $fields['tag']['fields'][] = 'id';
            $remove_id_field = true;
        }
        $tags = TagsTable::model()->fetchAll(array(
            'id IN (?)'=>$ids,
        ), $fields['tag']['fields']);
        
        if(!empty($fields['counter'])){
            //获取所有相关的counter
            $counters = TagCounterService::service()->mget($ids, $fields['counter']);
        }
        
        $return = array_fill_keys($ids, array());
        foreach($tags as $t){
            $tag['tag'] = $t;
            
            //counter
            if(isset($counters)){
                $tag['counter'] = $counters[$t['id']];
            }
            
            if($remove_id_field){
                //移除id字段
                unset($tag['tag']['id']);
                if(empty($tag['tag'])){
                    unset($tag['tag']);
                }
            }
            
            $return[$t['id']] = $tag;
        }
        
        return $return;
    }
    
    /**
     * 判断一个标签是否存在（禁用的标签也视为存在）
     * @param string $title
     * @param array $conditions 附加条件（例如编辑标签的时候，判断重复需要传入id != tag_id的条件）
     * @return int|bool 若存在，返回标签ID，若不存在，返回false
     */
    public static function isTagExist($title, $conditions = array()){
        if($title){
            $tag = TagsTable::model()->fetchRow(array(
                    'title = ?'=>$title,
                ) + $conditions, 'id');
            if($tag){
                return $tag['id'];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    /**
     * 创建一个标签，并返回标签ID
     * @param string $title 标签
     * @return int 标签ID
     */
    public function create($title){
        //判断标签是否存在，若已存在，直接返回标签ID
        $tag = self::isTagExist($title);
        if($tag){
            return $tag;
        }
        
        $tag_id = TagsTable::model()->insert(array(
            'title'=>$title,
            'user_id'=>\F::app()->current_user,
            'create_time'=>\F::app()->current_time,
        ));
        
        TagCounterTable::model()->insert(array(
            'tag_id'=>$tag_id,
        ));
        
        return $tag_id;
    }
}