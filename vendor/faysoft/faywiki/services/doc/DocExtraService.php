<?php
namespace cms\services\doc;

use fay\core\Loader;
use fay\core\Service;
use fay\helpers\FieldsHelper;
use faywiki\models\tables\WikiDocExtraTable;

class DocExtraService extends Service{
    /**
     * 默认返回字段
     */
    public static $default_fields = array('seo_title', 'seo_keywords', 'seo_description');
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 获取文档计数信息
     * @param int $doc_id 文档ID
     * @param string $fields 字段（doc_extra表字段）
     * @return array 返回包含文档meta信息的一维数组
     */
    public function get($doc_id, $fields = null){
        $fields = new FieldsHelper($fields ? $fields : self::$default_fields,
            '',
            WikiDocExtraTable::model()->getFields()
        );
        
        return WikiDocExtraTable::model()->fetchRow(array(
            'doc_id = ?'=>$doc_id,
        ), $fields->getFields());
    }
    
    /**
     * 批量获取文档计数信息
     * @param array $doc_ids 文档ID一维数组
     * @param string $fields 字段（doc_extra表字段）
     * @return array 返回以文档ID为key的二维数组
     */
    public function mget($doc_ids, $fields = null){
        if(!$doc_ids){
            return array();
        }
        
        $fields = new FieldsHelper(
            $fields ? $fields : self::$default_fields,
            '',
            WikiDocExtraTable::model()->getFields()
        );
        
        //批量搜索，必须先得到doc_id
        if(!$fields->hasField('doc_id')){
            $fields->addFields('doc_id');
            $remove_doc_id = true;
        }else{
            $remove_doc_id = false;
        }
        $metas = WikiDocExtraTable::model()->fetchAll(array(
            'doc_id IN (?)'=>$doc_ids,
        ), $fields->getFields(), 'doc_id');
        $return = array_fill_keys($doc_ids, array());
        foreach($metas as $m){
            $p = $m['doc_id'];
            if($remove_doc_id){
                unset($m['doc_id']);
            }
            $return[$p] = $m;
        }
        return $return;
    }
    
    /**
     * 将extra信息装配到$docs中
     * @param array $docs 包含文档信息的三维数组
     *   若包含$docs.doc.id字段，则以此字段作为文档ID
     *   若不包含$docs.doc.id，则以$docs的键作为文档ID
     * @param null|string $fields 字段（doc_extra表字段）
     */
    public function assemble(&$docs, $fields = null){
        //获取所有文档ID
        $doc_ids = array();
        foreach($docs as $k => $p){
            if(isset($p['doc']['id'])){
                $doc_ids[] = $p['doc']['id'];
            }else{
                $doc_ids[] = $k;
            }
        }
        
        $extra_map = $this->mget($doc_ids, $fields);
        
        foreach($docs as $k => $p){
            if(isset($p['doc']['id'])){
                $doc_id = $p['doc']['id'];
            }else{
                $doc_id = $k;
            }
            
            $p['extra'] = $extra_map[$doc_id];
            
            $docs[$k] = $p;
        }
    }
}