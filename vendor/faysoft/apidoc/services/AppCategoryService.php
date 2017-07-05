<?php
namespace apidoc\services;

use fay\core\db\Table;
use fay\core\Loader;
use fay\models\GroupTreeModel;

class AppCategoryService extends GroupTreeModel{
    /**
     * 归档字段
     * @var string
     */
    protected $group_field = 'app_id';

    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }

    /**
     * 获取表model
     * @return Table
     */
    protected function getModel(){
        return \F::table('apidoc\models\tables\ApidocApiCategoriesTable');
    }
}