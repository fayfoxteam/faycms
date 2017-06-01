<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;

/**
 * 文档
 */
class DocController extends ApiController{
    /**
     * 默认返回字段
     */
    protected $default_fields = array(
        'doc' => array(
            'fields' => array(
                'id', 'user_id', 'cat_id', 'title', 'abstract', 'thumbnail', 'create_time', 'write_lock',
            )
        ),
        'category' => array(
            'fields' => array(
                'id', 'title', 'alias',
            )
        ),
        'user' => array(
            'fields' => array(
                'id', 'nickname', 'avatar',
            )
        )
    );
}