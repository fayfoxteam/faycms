<div class="row">
	<div class="col-5">
		<div class="form-field">
			<label class="title">小工具实例</label>
			<div class="widget-list" id="inactive-widget-list">
			<?php if(isset($widgets) && is_array($widgets)){
				foreach($widgets as $widget){
					if($widget['widgetarea']) continue;
					$this->renderPartial('_widget_item', array(
						'widget'=>$widget,
					));
				}
			}?>
			</div>
		</div>
	</div>
	<div class="col-7" id="widgetarea-list">
	<?php foreach($widgetareas as $wa){?>
		<div class="box" data-alias="<?php echo $wa['alias']?>">
			<div class="box-title">
				<a class="tools toggle" title="点击以切换"></a>
				<h4><?php echo $wa['description'], ' - ', $wa['alias']?></h4>
			</div>
			<div class="box-content widget-list">
			<?php if(isset($widgets) && is_array($widgets)){
				foreach($widgets as $widget){
					if($widget['widgetarea'] != $wa['alias']) continue;
					$this->renderPartial('_widget_item', array(
						'widget'=>$widget,
					));
				}
			}?>
			</div>
		</div>
	<?php }?>
	</div>
</div>
<script>
var widgetarea = {
	'dragsort':function(){
		system.getScript(system.assets('js/jquery.dragsort-0.5.1.js'), function(){
			$('.widget-list').dragsort({
				'itemSelector': '.widget-item',
				//'dragSelector': '.widget-item-selector',
				'dragBetween': true,
				'placeHolderTemplate': '<div class="widget-item holder"></div>',
				'dragSelectorExclude': 'strong,span',
				'dragEnd':function(){
					var widgetareas = {};
					$('#widgetarea-list .box').each(function(){
						var widgetarea = $(this).attr('data-alias')
						widgetareas[widgetarea] = [];
						$(this).find('.widget-item').each(function(){
							widgetareas[widgetarea].push($(this).attr('data-widget-id'));
						});
					});
					$.ajax({
						'type': 'POST',
						'url': system.url('admin/widgetarea/set-widgets'),
						'data': widgetareas,
						'dataType': 'json',
						'cache': false,
						'success': function(resp){
							
						}
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