<?php
use fay\helpers\Html;

$widget_instance = F::app()->widget->get($widget['widget_name'], true);
?>
<div class="widget-item" data-widget-id="<?php echo $widget['id']?>">
	<a class="widget-item-selector"></a>
	<div class="widget-item-container">
		<strong><?php echo Html::tag('span', array(
			'title'=>'小工具名称',
		), $widget_instance->title), ' - ', $widget['widget_name']?></strong>
		<span class="operations"><?php
			echo Html::link('编辑', array('admin/widget/edit', array(
				'id'=>$widget['id'],
			)), array(), true);
			echo Html::link('删除', array('admin/widget/remove-instance', array(
				'id'=>$widget['id'],
			)), array(
				'class'=>'fc-red remove-link',
			), true);
		?></span>
		<p class="fc-grey"><?php echo Html::tag('span', array(
			'title'=>'小工具实例描述',
		), $widget['description'] ? $widget['description'] : '无描述'), ' - ', Html::tag('span', array(
			'title'=>'小工具实例别名',
		), $widget['alias'])?></p>
	</div>
</div>