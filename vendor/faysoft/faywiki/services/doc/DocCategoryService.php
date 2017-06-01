<?php
namespace faywiki\services\doc;

use cms\services\CategoryService;
use fay\core\Loader;
use fay\core\Service;
use faywiki\models\tables\WikiDocsTable;

class DocCategoryService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }

    /**
     * 更新文档分类文档数（创建或编辑文档时调用）
     * @param int|null $old_cat_id 文档原分类
     * @param int|null $new_cat_id 文档新分类
     * @param int|null $old_status 文档原状态
     * @param int|null $new_status 文档新状态
     */
    public function updateCatCount($old_cat_id, $new_cat_id, $old_status, $new_status){
        if($old_cat_id === null){
            if($new_cat_id && $new_status == WikiDocsTable::STATUS_PUBLISHED){
                //$old_cat_id为null，说明是新增文档，若分类非0，且文档状态为已发布，分类文档数加一
                CategoryService::service()->incr($new_cat_id);
            }
            //从这里开始，以下都是编辑文档的情况
        }else if($old_status == WikiDocsTable::STATUS_PUBLISHED && $new_status != WikiDocsTable::STATUS_PUBLISHED){
            //本来处于已发布状态，编辑后变成未发布：原分类文档数减一
            CategoryService::service()->decr($old_cat_id);
        }else if($old_status != WikiDocsTable::STATUS_PUBLISHED && $new_status == WikiDocsTable::STATUS_PUBLISHED){
            //本来处于未发布状态，编辑后变成已发布：新分类文档数加一
            CategoryService::service()->incr($new_cat_id);
        }else if($old_status == WikiDocsTable::STATUS_PUBLISHED &&
            ($new_status == WikiDocsTable::STATUS_PUBLISHED || $new_status === null) &&
            $old_cat_id != $new_cat_id
        ){
            //本来处于已发布状态，且编辑后还是已发布或未编辑状态，且编辑了分类：原分类文档数减一，新分类文档数加一
            CategoryService::service()->decr($old_cat_id);
            CategoryService::service()->incr($new_cat_id);
        }
    }
}