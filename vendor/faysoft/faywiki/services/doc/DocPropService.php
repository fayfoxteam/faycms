<?php
namespace faywiki\services\doc;

use cms\services\CategoryService;
use cms\services\prop\ItemPropService;
use cms\services\prop\PropService;
use cms\services\prop\PropUsageInterface;
use fay\core\db\Table;
use fay\core\Loader;
use fay\core\Service;
use fay\helpers\FieldItem;
use faywiki\models\tables\PropsTable;
use faywiki\models\tables\WikiDocPropIntTable;
use faywiki\models\tables\WikiDocPropTextTable;
use faywiki\models\tables\WikiDocPropTitleAliasTable;
use faywiki\models\tables\WikiDocPropVarcharTable;
use faywiki\models\tables\WikiDocsTable;

class DocPropService extends Service implements PropUsageInterface{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }

    /**
     * 获取用途显示名
     * @return string
     */
    public function getUsageName(){
        return '百科文档分类属性';
    }

    /**
     * 获取用途编号
     * @return int
     */
    public function getUsageType(){
        return PropsTable::USAGE_WIKI_DOC;
    }

    /**
     * 获取用途具体记录的标题。
     * 例如：用途是文档分类属性，则根据分类Id，获取分类标题
     * @param int $id
     * @return string
     * @throws DocErrorException
     */
    public function getUsageItemTitle($id){
        $cat = CategoryService::service()->get($id, 'title');
        if(!$cat){
            throw new DocErrorException("指定分类ID[{$id}]不存在");
        }
        return $cat['title'];
    }

    /**
     * 根据文档ID，获取属性用途（实际上就是文档分类）
     * @param int $doc_id
     * @return array
     * @throws DocErrorException
     */
    public function getUsages($doc_id){
        $doc = WikiDocsTable::model()->find($doc_id, 'cat_id');
        if(!$doc){
            throw new DocErrorException("指定文档ID[{$doc_id}]不存在");
        }

        return array($doc['cat_id']);
    }

    /**
     * 根据主用途，获取关联用途（实际上就是根据文档分类，获取其父节点）
     * @param int $cat_id
     * @return array
     */
    public function getSharedUsages($cat_id){
        //对于文档来说，必然是根据一个分类获取其父分类作为关联用途
        //所以这个参数如果是数组，取第一项
        if(is_array($cat_id)){
            if(!$cat_id){
                return array();
            }
            $cat_id = $cat_id[0];
        }
        return CategoryService::service()->getParentIds($cat_id, '_system_wiki_doc', false);
    }

    /**
     * 根据数据类型，获取相关表model
     * @param string $data_type
     * @return Table
     * @throws DocErrorException
     */
    public function getModel($data_type){
        switch($data_type){
            case 'int':
                return WikiDocPropIntTable::model();
                break;
            case 'varchar':
                return WikiDocPropVarcharTable::model();
                break;
            case 'text':
                return WikiDocPropTextTable::model();
            default:
                throw new DocErrorException("不支持的数据类型[{$data_type}]");
        }
    }

    /**
     * 获取属性名称别名表model
     * @return WikiDocPropTitleAliasTable
     */
    public function getTitleAliasModel(){
        return WikiDocPropTitleAliasTable::model();
    }

    /**
     * 将props信息装配到$docs中
     * @param array $docs 包含文档信息的三维数组
     *   若包含$docs.doc.id字段，则以此字段作为文档ID
     *   若不包含$docs.doc.id，则以$docs的键作为文档ID
     * @param null|string $fields 属性列表
     */
    public function assemble(&$docs, $fields = null){
        $fields = new FieldItem($fields, 'props');
        if($fields->hasField('*') || !$fields->getFields()){
            $props = null;
        }else{
            $props = PropService::service()->mget($fields->getFields(), PropsTable::USAGE_WIKI_DOC);
        }

        foreach($docs as $k => $p){
            if(isset($p['doc']['id'])){
                $doc_id = $p['doc']['id'];
            }else{
                $doc_id = $k;
            }

            $item_prop = new ItemPropService($doc_id, $this);
            $p['props'] = $item_prop->getPropSet($props);

            $docs[$k] = $p;
        }
    }

    /**
     * 根据分类id，获取所有相关属性
     * @param int $cat_id
     * @return array
     */
    public function getPropsByCatId($cat_id){
        $parents = CategoryService::service()->getParentIds($cat_id, '_system_wiki_doc', false);

        return PropService::service()->getPropsByUsage(array($cat_id), PropsTable::USAGE_WIKI_DOC, $parents);
    }

    /**
     * 根据文档id，获取所有相关属性
     * @param int $doc_id
     * @return array
     * @throws DocErrorException
     */
    public function getPropsByDocId($doc_id){
        $doc = WikiDocsTable::model()->find($doc_id, 'cat_id');
        if(!$doc){
            throw new DocErrorException("指定文档ID[{$doc_id}]不存在");
        }

        return $this->getPropsByCatId($doc['cat_id']);
    }

    /**
     * 获取指定文档的属性集
     * @param int $doc_id
     * @param null|array $props
     * @return array
     */
    public function getPropSet($doc_id, $props = null){
        return $this->getItemProp($doc_id)->getPropSet($props);
    }

    /**
     * 创建属性集
     * @param int $doc_id
     * @param array $data
     * @param array $labels
     * @param null|array $props 若指定$props则只创建指定的属性，否则根据文档id，创建全部属性
     */
    public function createPropSet($doc_id, $data, $labels = array(), $props = null){
        if($props === null){
            $props = $this->getPropsByDocId($doc_id);
        }
        $this->getItemProp($doc_id)->createPropSet($props, $data, $labels);
    }

    /**
     * 更新属性集
     * @param int $doc_id
     * @param array $data
     * @param array $labels
     * @param null|array $props 若指定$props则只更新指定的属性，否则根据文档id，更新全部属性
     */
    public function updatePropSet($doc_id, $data, $labels = array(), $props = null){
        if($props === null){
            $props = $this->getPropsByDocId($doc_id);
        }
        $this->getItemProp($doc_id)->updatePropSet($props, $data, $labels);
    }

    /**
     * 获取文档属性类实例
     * @param int $doc_id
     * @return ItemPropService
     */
    protected function getItemProp($doc_id){
        return new ItemPropService($doc_id, $this);
    }
}