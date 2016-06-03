<?php
namespace fay\models\post;

use fay\models\tables\Props;

class Prop extends \fay\models\Prop{
    /**
     * @param string $class_name
     * @return Prop
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
	/**
	 * @see Prop::$models
	 * @var array
	 */
	protected $models = array(
		'varchar'=>'fay\models\tables\PostPropVarchar',
		'int'=>'fay\models\tables\PostPropInt',
		'text'=>'fay\models\tables\PostPropText',
	);
	
	/**
	 * @see Prop::$foreign_key
	 * @var string
	 */
	protected $foreign_key = 'post_id';
	
	/**
	 * @see Prop::$type
	 * @var string
	 */
	protected $type = Props::TYPE_POST_CAT;
}