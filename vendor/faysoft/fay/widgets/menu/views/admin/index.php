<?php
use fay\helpers\HtmlHelper;
use fay\models\tables\RolesTable;
use fay\services\user\UserRoleService;
?>
<div class="box">
    <div class="box-title">
        <h4>配置参数</h4>
    </div>
    <div class="box-content">
        <div class="form-field">
            <label class="title bold">顶级菜单</label>
            <?php echo F::form('widget')->select('top', HtmlHelper::getSelectOptions($menu), array(
                'class'=>'form-control mw400',
            ))?>
            <p class="fc-grey">仅显示所选菜单的子菜单（不包含所选菜单本身）</p>
        </div>
        <div class="form-field">
            <a href="javascript:;" class="toggle-advance" style="text-decoration:underline;">高级设置</a>
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
<script>
$(function(){
    $('.toggle-advance').on('click', function(){
        $(".advance").toggle();
    });
});
</script>