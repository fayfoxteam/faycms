<?php
use fay\helpers\HtmlHelper;

/**
 * @var $widget \fay\widgets\friendlinks\controllers\AdminController
 */
?>
<div class="box">
    <div class="box-title">
        <h4>标题</h4>
    </div>
    <div class="box-content">
        <div class="form-field">
            <?php echo F::form('widget')->inputText('title', array(
                'class'=>'form-control',
            ))?>
            <p class="fc-grey">是否用到标题视模版而定，并不一定会显示。</p>
        </div>
    </div>
</div>
<div class="box">
    <div class="box-title">
        <h4>数据</h4>
    </div>
    <div class="box-content">
        <div class="dragsort-list" id="widget-listing-values">
        <?php foreach($widget->config['data'] as $v){?>
            <div class="dragsort-item">
                <a class="dragsort-rm" href="javascript:;"></a>
                <a class="dragsort-item-selector"></a>
                <div class="dragsort-item-container">
                    <?php echo HtmlHelper::textarea("data[]", $v, array(
                        'class'=>'form-control h60 autosize',
                    ));?>
                </div>
                <div class="clear"></div>
            </div>
        <?php }?>
        </div>
        <?php echo HtmlHelper::link('添加', 'javascript:;', array(
            'class'=>'btn mt5',
            'id'=>'widget-add-value-link',
        ))?>
    </div>
</div>
<div class="box">
    <div class="box-title">
        <h4>渲染模板</h4>
    </div>
    <div class="box-content">
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
<script>
$(function(){
    $(document).on('click', '#widget-add-value-link', function(){
        $('#widget-listing-values').append(['<div class="dragsort-item">',
            '<a class="dragsort-rm" href="javascript:;"></a>',
            '<a class="dragsort-item-selector"></a>',
            '<div class="dragsort-item-container">',
                '<textarea name="data[]" class="form-control h60 autosize"></textarea>',
            '</div>',
            '<div class="clear"></div>',
        '</div>'].join(''));
        autosize($('#widget-listing-data .dragsort-item:last-child textarea'));
    });
});
</script>