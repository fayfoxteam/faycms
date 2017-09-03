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
            <p class="fc-grey">若为空，则显示顶级分类的标题</p>
        </div>
        <div class="form-field">
            <label class="title bold">分类</label>
            <?php echo F::form('widget')->select('top', HtmlHelper::getSelectOptions($cats), array(
                'class'=>'form-control mw400',
            ))?>
        </div>
        <div class="form-field">
            <label class="title bold">显示页面数</label>
            <?php echo F::form('widget')->inputText('number', array(
                'class'=>'form-control mw150',
            ), 5)?>
        </div>
        <div class="form-field">
            <label class="title bold">无数据时是否显示小工具</label>
            <?php echo F::form('widget')->inputRadio('show_empty', 1, array(
                'label'=>'是',
            ))?>
            <?php echo F::form('widget')->inputRadio('show_empty', 0, array(
                'label'=>'否',
            ), true)?>
        </div>
        <div class="form-field">
            <a href="javascript:" class="toggle" data-src="#widget-advance-setting"><i class="fa fa-caret-down mr5"></i>高级设置</a>
        </div>
        <div id="widget-advance-setting" class="<?php if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN))echo 'hide';?>">
            <div class="form-field">
                <label class="title bold">链接格式<span class="fc-red">（若非开发人员，请不要修改此配置）</span></label>
                <?php
                    echo HtmlHelper::inputRadio('uri', 'page/{$id}', !isset($widget->config['uri']) || $widget->config['uri'] == 'page/{$id}', array(
                        'label'=>'page/{$id}',
                    ));
                    echo HtmlHelper::inputRadio('uri', 'page/{$alias}', isset($widget->config['uri']) && $widget->config['uri'] == 'page/{$alias}', array(
                        'label'=>'page/{$alias}',
                    ));
                    echo HtmlHelper::inputRadio('uri', 'page-{$id}', isset($widget->config['uri']) && $widget->config['uri'] == 'page-{$id}', array(
                        'label'=>'page-{$id}',
                    ));
                    echo HtmlHelper::inputRadio('uri', 'page-{$alias}', isset($widget->config['uri']) && $widget->config['uri'] == 'page-{$alias}', array(
                        'label'=>'page-{$alias}',
                    ));
                    echo HtmlHelper::inputRadio('uri', '', isset($widget->config['uri']) && !in_array($widget->config['uri'], array(
                        'page/{$id}', 'page-{$id}',
                    )), array(
                        'label'=>'其它',
                    ));
                    echo HtmlHelper::inputText('other_uri', isset($widget->config['uri']) && !in_array($widget->config['uri'], array(
                        'page/{$id}', 'page/{$alias}', 'page-{$id}', 'page-{$alias}',
                    )) ? $widget->config['uri'] : '', array(
                        'class'=>'form-control mw150 ib',
                    ));
                ?>
                <p class="fc-grey">
                    <code>{$id}</code>代表“文章ID”。
                    不要包含base_url部分
                </p>
            </div>
            <?php echo F::app()->view->renderPartial('admin/widget/_template_field')?>
        </div>
    </div>
</div>