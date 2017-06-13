<?php
namespace cms\validators;

use cms\services\post\PostService;
use fay\core\Validator;
use fay\helpers\NumberHelper;

/**
 * 验证指定文章ID是否存在
 */
class PostIDValidator extends Validator{
    /**
     * @var bool 若为true，则未发布文章ID视为不存在的文章ID
     */
    protected $only_published = true;

    /**
     * 错误描述
     */
    public $message = '{$attribute}不存在';

    /**
     * 错误码
     */
    public $code = 'invalid-parameter:{$field}-is-not-exist';

    public function validate($value){
        if(!NumberHelper::isInt($value)){
            //不是数字，直接返回false
            return $this->addError($this->message, $this->code);
        }
        if(PostService::isPostIdExist($value, $this->only_published)){
            return true;
        }else{
            return $this->addError($this->message, $this->code);
        }
    }
}