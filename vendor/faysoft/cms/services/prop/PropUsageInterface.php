<?php
namespace cms\services\prop;

use fay\core\db\Table;

/**
 * 属性用途接口，定义一些与属性用途相关的操作
 */
interface PropUsageInterface{
    /**
     * 获取显示名
     * @return string
     */
    public function getUsageName();

    /**
     * 获取用途编号
     * @return int
     */
    public function getUsageType();

    /**
     * 根据外键id，获取主用途ID
     * 例如：根据文章ID，获取文章分类ID，文章自定义属性是挂载在分类上的
     * @param int $relation_id
     * @return int|array
     */
    public function getUsages($relation_id);

    /**
     * 根据主用途ID，获取共享属性的关联ID
     * 例如：根据文章分类ID，获取其所有父节点ID，可能共享属性的其他相关ID
     * @param int $usage_id 用途ID。例如：文章分类ID
     * @return array
     */
    public function getSharedUsages($usage_id);

    /**
     * 根据数据类型，获取相关表model
     * @param string $data_type 至少需要实现int，varchar，text类型。
     *  此类表必须包含3个字段：refer, prop_id, content
     *  其中content字段类型分别为：int(10), varchar(255), text
     * @return Table
     */
    public function getModel($data_type);
}