<?php
$show_alias = F::form('setting')->getData('show_alias', 0);
?>
<div class="row">
    <div class="col-7" id="widgetarea-list">
    <?php foreach($widgetareas as $wa){?>
        <div class="box" data-alias="<?php echo $wa['alias']?>">
            <div class="box-title">
                <h4><?php
                    echo $wa['description'];
                    if($show_alias){
                        echo ' - ', $wa['alias'];
                    }?></h4>
            </div>
            <div class="box-content widget-list">
            <?php if(isset($widgets) && is_array($widgets)){
                foreach($widgets as $widget){
                    if($widget['widgetarea'] != $wa['alias']) continue;
                    $this->renderPartial('_widget_item', array(
                        'widget'=>$widget,
                        'show_alias'=>$show_alias,
                    ));
                }
            }?>
            </div>
        </div>
    <?php }?>
    </div>
    <div class="col-5">
        <div class="form-field">
            <label class="title bold">小工具实例</label>
            <div class="widget-list" id="inactive-widget-list">
            <?php if(isset($widgets) && is_array($widgets)){
                foreach($widgets as $widget){
                    if($widget['widgetarea']) continue;
                    $this->renderPartial('_widget_item', array(
                        'widget'=>$widget,
                        'show_alias'=>$show_alias,
                    ));
                }
            }?>
            </div>
        </div>
    </div>
</div>
<script>
var widgetarea = {
    'dragsort':function(){
        system.getScript(system.assets('js/jquery.dragsort-0.5.2.js'), function(){
            $('.widget-list').dragsort({
                'itemSelector': '.widget-item',
                'dragSelector': '.widget-item',//若不指定，且第一个框中没可拖动元素，则其他框也不可拖动，这算是插件的bug吧
                'dragBetween': true,
                'placeHolderTemplate': '<div class="widget-item holder"></div>',
                'dragSelectorExclude': 'strong,span',
                'dragEnd':function(){
                    var widgetareas = {};
                    //当前可见的小工具域
                    
                    
                    //当前可见的小工具域中关联的小工具
                    $('#widgetarea-list').find('.box:visible').each(function(){
                        var widgetarea = $(this).attr('data-alias');
                        widgetareas[widgetarea] = [];
                        $(this).find('.widget-item').each(function(){
                            widgetareas[widgetarea].push($(this).attr('data-widget-id'));
                        });
                    });
                    $.ajax({
                        'type': 'POST',
                        'url': system.url('cms/admin/widgetarea/set-widgets'),
                        'data': widgetareas,
                        'cache': false
                    });
                }
            });
        });
    },
    'init':function(){
        this.dragsort();
    }
};
$(function(){
    widgetarea.init();
});
</script>