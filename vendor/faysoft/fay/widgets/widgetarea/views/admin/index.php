<?php
use fay\models\tables\RolesTable;
use fay\services\user\UserRoleService;

/**
 * @var $widgetareas array
 */
?>
<div class="box">
    <div class="box-title">
        <h4>配置参数</h4>
    </div>
    <div class="box-content">
        <div class="form-field">
            <label class="title bold">选择小工具域</label>
            <?php echo F::form('widget')->select('alias', $widgetareas, array(
                'class'=>'form-control mw400',
            ))?>
        </div>
        <div class="advance <?php if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN))echo 'hide';?>">
            <div class="form-field">
                <label class="title bold">渲染模版</label>
                <?php echo F::form('widget')->textarea('template', array(
                    'class'=>'form-control h90 autosize',
                    'id'=>'code-editor',
                ))?>
                <p class="fc-grey mt5">
                    若模版内容符合正则<code>/^[\w_-]+(\/[\w_-]+)+$/</code>，
                    即类似<code>frontend/widget/template</code><br />
                    则会调用当前application下符合该相对路径的view文件。<br />
                    否则视为php代码<code>eval</code>执行。若留空，会调用默认模版。
                </p>
            </div>
        </div>
    </div>
</div>