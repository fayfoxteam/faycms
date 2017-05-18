<?php
use fay\helpers\HtmlHelper;

/**
 * @var $widget \cms\widgets\options\controllers\AdminController
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
        <h4>属性集</h4>
    </div>
    <div class="box-content">
        <div class="dragsort-list" id="widget-attr-list">
        <?php foreach($widget->config['data'] as $d){?>
            <div class="dragsort-item cf">
                <a class="dragsort-item-selector"></a>
                <div class="dragsort-item-container"><?php 
                    echo HtmlHelper::inputText('keys[]', $d['key'], array(
                        'class'=>'form-control fl',
                        'placeholder'=>'名称',
                        'wrapper'=>array(
                            'tag'=>'span',
                            'class'=>'ib col-5 fl',
                        ),
                    ));
                    echo HtmlHelper::textarea('values[]', $d['value'], array(
                        'class'=>'form-control autosize',
                        'placeholder'=>'值',
                        'wrapper'=>array(
                            'tag'=>'span',
                            'class'=>'ib col-7 fr',
                        ),
                    ));
                    echo HtmlHelper::link('删除', 'javascript:;', array(
                        'class'=>'btn btn-grey mt5 btn-sm fl widget-remove-attr-link',
                        'wrapper'=>array(
                            'tag'=>'span',
                            'class'=>'ib col-5 fl',
                        ),
                    ));
                ?></div>
            </div>
        <?php }?>
        </div>
    </div>
</div>
<div class="box">
    <div class="box-title">
        <h4>添加属性</h4>
    </div>
    <div class="box-content">
        <div class="cf"><?php 
            echo HtmlHelper::inputText('', '', array(
                'class'=>'form-control fl',
                'placeholder'=>'名称',
                'id'=>'widget-add-attr-key',
                'wrapper'=>array(
                    'tag'=>'span',
                    'class'=>'ib col-5 fl',
                ),
            ));
            echo HtmlHelper::textarea('', '', array(
                'class'=>'form-control autosize',
                'placeholder'=>'值',
                'id'=>'widget-add-attr-value',
                'wrapper'=>array(
                    'tag'=>'span',
                    'class'=>'ib col-7 fr',
                ),
            ));
            echo HtmlHelper::link('添加', 'javascript:;', array(
                'class'=>'btn mt5 btn-sm fl',
                'id'=>'widget-add-attr-link',
                'wrapper'=>array(
                    'tag'=>'span',
                    'class'=>'ib col-5 fr',
                ),
            ));
        ?></div>
    </div>
</div>
<?php F::app()->view->renderPartial('admin/widget/_template_box')?>
<script>
var widgetOptions = {
    'addAttr':function(){
        var $addAttrKey = $('#widget-add-attr-key');
        var $addAttrValue = $('#widget-add-attr-value');
        var $attrList = $('#widget-attr-list');
        $('#widget-add-attr-link').on('click', function(){
            if($addAttrKey.val() == ""){
                common.alert('名称不能为空');
            }else{
                $attrList.append(['<div class="dragsort-item cf">',
                    '<a class="dragsort-item-selector" style="cursor: pointer;"></a>',
                    '<div class="dragsort-item-container">',
                        '<span class="ib col-5 fl">',
                            '<input name="keys[]" type="text" class="form-control fl" placeholder="名称" value="', $addAttrKey.val(), '">',
                        '</span>',
                        '<span class="ib col-7 fr">',
                            '<textarea name="values[]" class="form-control autosize" placeholder="值">',
                                $addAttrValue.val(),
                            '</textarea>',
                        '</span>',
                        '<span class="ib col-5 fl">',
                            '<a class="btn btn-grey mt5 btn-sm fl widget-remove-attr-link" href="javascript:" title="删除">删除</a></div>',
                        '</span>',
                    '</div>',
                '</div>'].join(''));
                $addAttrKey.val('');
                $addAttrValue.val('');
                autosize($attrList.find('.autosize:last'));
                autosize.update($addAttrValue);
            }
        });
    },
    'removeAttr':function(){
        $('#widget-attr-list').on('click', '.widget-remove-attr-link', function(){
            if(confirm("您确定要删除此属性吗？")){
                $(this).parent().parent().parent().remove();
            }
        });
    },
    'init':function(){
        this.addAttr();
        this.removeAttr();
    }
};
$(function(){
    widgetOptions.init();
});
</script>