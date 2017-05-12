<?php
namespace fay\models;

use cms\models\tables\PropsRefersTable;
use fay\core\ErrorException;
use fay\core\Model;
use cms\models\tables\PropsTable;
use cms\models\tables\PropValuesTable;
use fay\core\Sql;
use fay\helpers\StringHelper;
use fay\helpers\ArrayHelper;

abstract class PropModel extends Model{
    /**
     * 表模型，需要包含int，varchar，text3种类型
     * 此类表必须包含3个字段：{$this->foreign_key}, prop_id, content
     * 其中content字段类型分别为：int(10), varchar(255), text
     * @var array
     */
    protected $models;
    
    /**
     * $this->models中表的外主键（例如文章附加属性，则对应外主键是文章ID：post_id）
     * @var string
     */
    protected $foreign_key;
    
    /**
     * 类型
     * @var int
     */
    protected $type;
    
    public function __construct(){
        if(!$this->models){
            throw new ErrorException(__CLASS__ . '::$models属性未指定');
        }
        if(!$this->foreign_key){
            throw new ErrorException(__CLASS__ . '::$foreign_key属性未指定');
        }
        if(!$this->type){
            throw new ErrorException(__CLASS__ . '::$type属性未指定');
        }
    }
}