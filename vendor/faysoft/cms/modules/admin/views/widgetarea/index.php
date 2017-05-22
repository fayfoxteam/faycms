<?php
/**
 * @var $widget_areas array
 * @var $widget_map array
 * @var $relate_widgets array
 * @var $relate_widget_ids array
 * @var $relate_widget_map array
 */
$show_alias = F::form('setting')->getData('show_alias', 0);
?>
<div class="row">
    <div class="col-7" id="widgetarea-list">
    <?php foreach($widget_areas as $wa){?>
        <div class="box" data-id="<?php echo $wa['id']?>">
            <div class="box-title">
                <h4><?php
                    echo $wa['description'];
                    if($show_alias){
                        echo ' - ', $wa['alias'];
                    }?></h4>
            </div>
            <div class="box-content widget-list">
            <?php
            if(isset($relate_widget_map[$wa['id']])){
                foreach($relate_widget_map[$wa['id']] as $relate_widget_id){
                    $this->renderPartial('_widget_item', array(
                        'widget'=>$widget_map[$relate_widget_id],
                        'show_alias'=>$show_alias,
                        'widget_area_id'=>$wa['id'],
                    ));
                }
            }?>
            </div>
        </div>
    <?php }?>
    </div>
    <div class="col-5">
        <div class="form-field">
            <label class="title bold">未关联小工具实例</label>
            <div class="widget-list" id="inactive-widget-list">
            <?php foreach($widget_map as $widget){
                if(in_array($widget['id'], $relate_widget_ids)){
                    continue;
                }
                $this->renderPartial('_widget_item', array(
                    'widget'=>$widget,
                    'show_alias'=>$show_alias,
                    'widget_area_id'=>0,
                ));
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
                    var widgetAreas = {};
                    //当前可见的小工具域中关联的小工具
                    $('#widgetarea-list').find('.box:visible').each(function(){
                        var widgetAreaId = $(this).attr('data-id');
                        widgetAreas[widgetAreaId] = [0];//预留一个空值
                        $(this).find('.widget-item').each(function(){
                            widgetAreas[widgetAreaId].push($(this).attr('data-widget-id'));
                        });
                    });
                    console.log(widgetAreas);
                    $.ajax({
                        'type': 'POST',
                        'url': system.url('cms/admin/widgetarea/set-widgets'),
                        'data': {
                            'widget_areas': widgetAreas
                        },
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