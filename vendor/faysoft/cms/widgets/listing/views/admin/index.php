<?php
use fay\helpers\HtmlHelper;

/**
 * @var $widget \cms\widgets\friendlinks\controllers\AdminController
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
            <p class="fc-grey mt5">是否用到标题视模版而定，并不一定会显示。</p>
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
                <a class="dragsort-rm" href="javascript:"></a>
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
<?php F::app()->view->renderPartial('admin/widget/_template_box')?>
<script>
$(function(){
    $(document).on('click', '#widget-add-value-link', function(){
        $('#widget-listing-values').append(['<div class="dragsort-item">',
            '<a class="dragsort-rm" href="javascript:"></a>',
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