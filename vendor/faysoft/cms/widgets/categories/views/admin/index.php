<?php
use fay\helpers\HtmlHelper;
use cms\models\tables\RolesTable;
use cms\services\user\UserRoleService;
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
            <label class="title bold">顶级分类</label>
            <?php echo F::form('widget')->select('top', HtmlHelper::getSelectOptions($cats), array(
                'class'=>'form-control mw400',
            ))?>
            <p class="fc-grey">仅显示所选分类的子分类（不包含所选分类本身）</p>
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
            <a href="javascript:" class="toggle-advance" style="text-decoration:underline;">高级设置</a>
            <span class="fc-red">（若非开发人员，请不要修改以下配置）</span>
        </div>
        <div class="advance <?php if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN))echo 'hide';?>">
            <div class="form-field">
                <label class="title bold">链接格式</label>
                <?php
                    echo HtmlHelper::inputRadio('uri', 'cat/{$id}', !isset($widget->config['uri']) || $widget->config['uri'] == 'cat/{$id}', array(
                        'label'=>'cat/{$id}',
                    ));
                    echo HtmlHelper::inputRadio('uri', 'cat/{$alias}', isset($widget->config['uri']) && $widget->config['uri'] == 'cat/{$alias}', array(
                        'label'=>'cat/{$alias}',
                    ));
                    echo HtmlHelper::inputRadio('uri', 'cat-{$id}', isset($widget->config['uri']) && $widget->config['uri'] == 'cat-{$id}', array(
                        'label'=>'cat-{$id}',
                    ));
                    echo HtmlHelper::inputRadio('uri', 'cat-{$alias}', isset($widget->config['uri']) && $widget->config['uri'] == 'cat-{$alias}', array(
                        'label'=>'cat-{$alias}',
                    ));
                    echo HtmlHelper::inputRadio('uri', '', isset($widget->config['uri']) && !in_array($widget->config['uri'], array(
                        'cat/{$id}', 'cat/{$alias}', 'cat-{$id}', 'cat-{$alias}',
                    )), array(
                        'label'=>'其它',
                    ));
                    echo HtmlHelper::inputText('other_uri', isset($widget->config['uri']) && !in_array($widget->config['uri'], array(
                        'cat/{$id}', 'cat/{$alias}', 'cat-{$id}', 'cat-{$alias}',
                    )) ? $widget->config['uri'] : '', array(
                        'class'=>'form-control mw150 ib',
                    ));
                ?>
                <p class="fc-grey">
                    <code>{$id}</code>代表“分类ID”。
                    <code>{$alias}</code>代表“分类别名”。
                    不要包含base_url部分。<br>
                    <span class="fc-orange">此配置项是否生效取决于模版代码</span>
                </p>
            </div>
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