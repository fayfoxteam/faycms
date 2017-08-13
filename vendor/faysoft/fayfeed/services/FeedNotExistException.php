<?php
namespace fayfeed\services;

/**
 * 指定动态不存在
 */
class FeedNotExistException extends \UnexpectedValueException{
    public function __construct($feed_id = 0, \Exception $previous = null){
        parent::__construct(
            $feed_id ? "指定动态ID[{$feed_id}]不存在" : '指定动态不存在',
            0,
            $previous
        );
    }
}