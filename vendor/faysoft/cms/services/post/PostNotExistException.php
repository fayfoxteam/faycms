<?php
namespace cms\services\post;

/**
 * 指定文章不存在
 */
class PostNotExistException extends \UnexpectedValueException{
    public function __construct($post_id = 0, \Exception $previous = null){
        parent::__construct(
            $post_id ? "指定文章ID[{$post_id}]不存在" : '指定文章不存在',
            0,
            $previous
        );
    }
}