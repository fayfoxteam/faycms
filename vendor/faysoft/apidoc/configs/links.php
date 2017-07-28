<?php
/**
 * 链接格式
 * 需要与configs/routes.php（url重写配置）配合使用，并实现对应的controller
 */
return array(
    /**
     * API详情页链接格式。可用参数：
     * - `{$id}`代表“API ID”
     * > 不要包含base_url部分
     * 也可以直接用匿名函数返回完整链接
     */
    'api'=>'apidoc/frontend/api/item?api_id={$id}',
    /**
     * 模型（Model）详情页链接格式。可用参数
     * - `{$id}`代表“模型ID”
     * > 不要包含base_url部分
     * 也可以直接用匿名函数返回完整链接
     */
    'model'=>'apidoc/frontend/model/item?model_id={$id}',
);