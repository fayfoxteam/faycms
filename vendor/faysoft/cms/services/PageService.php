<?php
namespace cms\services;

use cms\models\tables\CategoriesTable;
use cms\models\tables\PagesCategoriesTable;
use cms\models\tables\PagesTable;
use cms\services\file\FileService;
use fay\core\Loader;
use fay\core\Service;
use fay\core\Sql;
use fay\helpers\StringHelper;

class PageService extends Service{
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    public function getPageCats($id, $fields = '*'){
        $sql = new Sql();
        return $sql->from(array('pc'=>'pages_categories'), '')
            ->joinLeft(array('c'=>'categories'), 'pc.cat_id = c.id', $fields)
            ->where("pc.page_id = {$id}")
            ->fetchAll();
    }
    
    public function getPageCatIds($id){
        return PagesCategoriesTable::model()->fetchCol('cat_id', "page_id = {$id}");
    }
    
    /**
     * 根据分类别名获取页面
     * @param string $alias
     * @param int $limit
     * @param mixed $fields
     * @param bool $children 若为true，则会返回该分类及其所有子分类对应的页面
     * @return array
     */
    public function getByCatAlias($alias, $limit = 10, $fields = '!content', $children = false){
        $cat = CategoriesTable::model()->fetchRow(array(
            'alias = ?'=>$alias
        ), 'id,left_value,right_value');
        
        $sql = new Sql();
        $sql->from(array('p'=>'pages'), PagesTable::model()->formatFields($fields))
            ->joinLeft(array('pc'=>'pages_categories'), 'p.id = pc.page_id')
            ->where(array(
                'delete_time = 0',
                'status = '.PagesTable::STATUS_PUBLISHED,
            ))
            ->order('sort, id DESC')
            ->distinct(true)
            ->limit($limit);
        if($children){
            $all_cats = CategoriesTable::model()->fetchCol('id', array(
                'left_value >= '.$cat['left_value'],
                'right_value <= '.$cat['right_value'],
            ));
            $sql->where(array(
                'pc.cat_id IN ('.implode(',', $all_cats).')',
            ));
        }else{
            $sql->orWhere(array(
                "pc.cat_id = {$cat['id']}",
            ));
        }
        return $sql->fetchAll();
    }
    
    /**
     * 根据别名获取单页
     * @param string $alias
     * @param string $fields
     * @return array|bool
     */
    public function getByAlias($alias, $fields = '*'){
        $page = PagesTable::model()->fetchRow(array(
            'alias = ?'=>$alias,
        ), $fields);

        if(isset($page['thumbnail'])){
            $page['thumbnail'] = FileService::get($page['thumbnail']);
        }
        
        return $page;
    }
    
    /**
     * 根据ID获取单页
     * @param int $id
     * @param string $fields
     * @return array|bool
     */
    public function getById($id, $fields = '*'){
        $page = PagesTable::model()->find($id, $fields);

        if(isset($page['thumbnail'])){
            $page['thumbnail'] = FileService::get($page['thumbnail']);
        }

        return $page;
    }

    /**
     * 获取单页
     * @param int|string $page
     *  - 若为数字，视为单页ID获取
     *  - 若为字符串，视为单页别名获取
     * @param string $fields
     * @return array|bool
     */
    public function get($page, $fields = '*'){
        if(StringHelper::isInt($page)){
            return $this->getById($page, $fields);
        }else{
            return $this->getByAlias($page, $fields);
        }
    }
    
    /**
     * 根据页面状态获取页面数
     * @param int $status 页面状态
     * @return string
     */
    public function getCount($status = null){
        $conditions = array('delete_time = 0');
        if($status !== null){
            $conditions['status = ?'] = $status;
        }
        $result = PagesTable::model()->fetchRow($conditions, 'COUNT(*)');
        return $result['COUNT(*)'];
    }
    
    /**
     * 获取已删除的页面数
     * @return string
     */
    public function getDeletedCount(){
        $result = PagesTable::model()->fetchRow('delete_time > 0', 'COUNT(*)');
        return $result['COUNT(*)'];
    }

    /**
     * 根据ID返回别名。
     * 若指定ID不存在，返回false
     * @param int $id
     * @return string|false
     */
    public function getAliasByID($id){
        $page = $this->get($id, 'alias');
        return $page ? $page['alias'] : false;
    }

    /**
     * 根据别名返回ID。
     * 若指定别名不存在，返回false
     * @param string $alias
     * @return string|false
     */
    public function getIDByAlias($alias){
        $page = $this->get($alias, 'id');
        return $page ? $page['id'] : false;
    }
}