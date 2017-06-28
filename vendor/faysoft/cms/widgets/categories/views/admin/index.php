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
            <p class="fc-grey mt5">若为空，则显示顶级分类的标题</p>
        </div>
        <div class="form-field">
            <label class="title bold">默认顶级分类</label>
            <?php echo F::form('widget')->select('top', HtmlHelper::getSelectOptions($cats), array(
                'class'=>'form-control mw400',
            ))?>
            <p class="fc-grey mt5">仅显示所选分类的子分类（不包含所选分类本身）</p>
        </div>
        <div class="form-field">
            <label class="title bold">是否体现层级关系</label>
            <?php echo F::form('widget')->inputRadio('hierarchical', 1, array(
                'label'=>'是',
            ))?>
            <?php echo F::form('widget')->inputRadio('hierarchical', 0, array(
                'label'=>'否',
            ), true)?>
        </div>
        <div class="form-field">
            <a href="javascript:" class="toggle" data-src="#widget-advance-setting"><i class="fa fa-caret-down mr5"></i>高级设置</a>
            <span class="fc-red">（若非开发人员，请不要修改以下配置）</span>
        </div>
        <div id="widget-advance-setting" class="<?php if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN))echo 'hide';?>">
            <div class="form-field">
                <label class="title bold">分类字段</label>
                <?php echo F::form('widget')->inputText('cat_key', array(
                    'class'=>'form-control mw150',
                ))?>
                <p class="fc-grey mt5">
                    分类字段名（分类ID或者别名）<br>
                    若连接中包含分类字段，则以此分类作文顶级分类。<br>
                    若希望固定显示指定分类的子分类，将此字段留空即可。
                </p>
            </div>
            <div class="form-field">
                <label class="title bold">无子分类展示平级分类</label>
                <?php echo F::form('widget')->inputRadio('show_sibling_when_terminal', 1, array(
                    'label'=>'是',
                ))?>
                <?php echo F::form('widget')->inputRadio('show_sibling_when_terminal', 0, array(
                    'label'=>'否',
                ), true)?>
                <p class="fc-grey">
                    当前节点没有子节点，展示当前节点及平级节点（根节点除外）。
                </p>
            </div>
            <?php F::app()->view->renderPartial('admin/widget/_template_field')?>
        </div>
    </div>
</div>