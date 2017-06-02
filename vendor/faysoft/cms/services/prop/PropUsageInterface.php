<?php
namespace cms\services\prop;

use fay\core\db\Table;

/**
 * 属性用途接口，定义一些与属性用途相关的操作
 */
interface PropUsageInterface{
    /**
     * 获取用途显示名
     * @return string
     */
    public function getUsageName();

    /**
     * 获取用途类型编号
     * @return int
     */
    public function getUsageType();

    /**
     * 获取用途具体记录的标题。
     * 例如：用途是文章分类属性，则根据分类Id，获取分类标题
     * @param int $id
     * @return string
     */
    public function getUsageItemTitle($id);

    /**
     * 根据外键id，获取主用途ID
     * 例如：根据文章ID，获取文章分类ID，文章自定义属性是挂载在分类上的
     * @param int $relation_id
     * @return int|array
     */
    public function getUsages($relation_id);

    /**
     * 根据主用途ID，获取共享属性的关联ID
     * 例如：根据文章分类ID，获取其所有父节点ID，即可能共享属性的其他相关ID
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

    /**
     * 获取属性名称别名表model
     * @return Table
     */
    public function getLabelModel();
}