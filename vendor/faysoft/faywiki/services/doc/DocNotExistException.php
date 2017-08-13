<?php
namespace faywiki\services\doc;

/**
 * 指定文档不存在
 */
class DocNotExistException extends \UnexpectedValueException{
    public function __construct($doc_id = 0, \Exception $previous = null){
        parent::__construct(
            $doc_id ? "指定文档ID[{$doc_id}]不存在" : '指定文档不存在',
            0,
            $previous
        );
    }
}