<?php
namespace cms\services\prop;

use fay\core\db\Table;

/**
 * 属性类型接口，定义一些与属性类型相关的操作
 */
interface PropTypeInterface{
    /**
     * 获取显示名
     * @return string
     */
    public function getTypeName();

    /**
     * 根据外键id，获取外键id相关的其他id
     * 例如：根据文章分类ID，获取其所有父节点ID，可能共享属性的其他相关ID
     * @param int $refer 引用ID。例如：文章分类ID
     * @return array
     */
    public function getRelationRefers($refer);

    /**
     * 根据数据类型，获取相关表model
     * @param string $data_type 至少需要实现int，varchar，text类型。
     *  此类表必须包含3个字段：refer, prop_id, content
     *  其中content字段类型分别为：int(10), varchar(255), text
     * @return Table
     */
    public function getModel($data_type);

    /**
     * 获取一个单体相关属性。例如：获取一篇文章的所有属性。
     * （只是获取拥有的属性，并不包含属性值）
     * @param int $id 单体ID。例如：文章ID
     * @return array
     */
    public function getItemProps($id);
}