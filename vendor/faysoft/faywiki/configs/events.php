<?php
return array(
    'after_controller_constructor'=>array(
        //Controller实例化后执行
        array(
            'handler'=>function(){
                //自定义属性分类
                \cms\services\prop\PropService::$usage_type_map[\faywiki\models\tables\PropsTable::USAGE_WIKI_DOC] = 'faywiki\services\doc\DocPropService';
            },
        ),
    ),
);