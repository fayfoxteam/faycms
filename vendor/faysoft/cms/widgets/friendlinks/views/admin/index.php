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
            <label class="title bold">标题</label>
            <?php echo F::form('widget')->inputText('title', array(
                'class'=>'form-control mw400',
            ))?>
            <p class="fc-grey">若为空，默认为“友情链接”</p>
        </div>
        <div class="form-field">
            <label class="title bold">分类</label>
            <?php echo F::form('widget')->select('cat_id', HtmlHelper::getSelectOptions($cats), array(
                'class'=>'form-control mw400',
            ))?>
        </div>
        <div class="form-field">
            <label class="title bold">显示链接数</label>
            <?php echo F::form('widget')->inputText('number', array(
                'class'=>'form-control mw150',
            ), 5)?>
        </div>
        <div class="form-field">
            <a href="javascript:" class="toggle" data-src="#widget-advance-setting"><i class="fa fa-caret-down mr5"></i>高级设置</a>
        </div>
        <div id="widget-advance-setting" class="<?php if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN))echo 'hide';?>">
            <?php echo F::app()->view->renderPartial('admin/widget/_template_field')?>
        </div>
    </div>
</div>