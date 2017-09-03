<?php
use cms\models\tables\RolesTable;
use cms\services\user\UserRoleService;
use fay\helpers\HtmlHelper;

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
            <a href="javascript:" class="toggle" data-src="#widget-advance-setting"><i class="fa fa-caret-down mr5"></i>高级设置</a>
        </div>
        <div id="widget-advance-setting" class="<?php if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN))echo 'hide';?>">
            <?php echo F::app()->view->renderPartial('admin/widget/_template_field')?>
        </div>
    </div>
</div>