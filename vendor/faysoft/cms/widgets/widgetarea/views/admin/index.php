<?php
use cms\models\tables\RolesTable;
use cms\services\user\UserRoleService;

/**
 * @var $widget_areas array
 */
$widget_area_map = array();
foreach($widget_areas as $wa){
    $widget_area_map[$wa['id']] = $wa['description'] . ' - ' . $wa['alias'];
}
?>
<div class="box">
    <div class="box-title">
        <h4>配置参数</h4>
    </div>
    <div class="box-content">
        <div class="form-field">
            <label class="title bold">选择小工具域</label>
            <?php echo F::form('widget')->select('alias', $widget_area_map, array(
                'class'=>'form-control mw400',
            ))?>
        </div>
        <div id="widget-advance-setting" class="<?php if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN))echo 'hide';?>">
            <?php echo F::app()->view->renderPartial('admin/widget/_template_field')?>
        </div>
    </div>
</div>