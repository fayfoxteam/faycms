<?php
use fay\helpers\Html;

$widget_instance = F::widget()->get($widget['widget_name'], true);
?>
<div class="widget-item <?php if(!$widget['enabled'])echo 'bl-yellow'?>" data-widget-id="<?php echo $widget['id']?>">
	<a class="widget-item-selector"></a>
	<div class="widget-item-container">
	<?php if($show_alias){?>
		<strong><?php echo Html::tag('span', array(
			'title'=>'小工具实例描述',
		), $widget['description'] ? $widget['description'] : '无描述'), ' - ', Html::tag('span', array(
			'title'=>'小工具实例别名',
		), $widget['alias'])?></strong>
		<span class="operations"><?php
			echo Html::link('编辑', array('admin/widget/edit', array(
				'id'=>$widget['id'],
			)), array(), true);
			echo Html::link('删除', array('admin/widget/remove-instance', array(
				'id'=>$widget['id'],
			)), array(
				'class'=>'fc-red remove-link',
			), true);
			echo Html::link('复制', array('admin/widget/copy', array(
				'id'=>$widget['id'],
			)), array(), true);
		?></span>
		<p class="fc-grey"><?php echo Html::tag('span', array(
			'title'=>'小工具名称',
		), $widget_instance->title), ' - ', $widget['widget_name']?></p>
	<?php }else{?>
		<?php echo Html::tag('strong', array(
			'title'=>'小工具实例描述',
		), $widget['description'] ? $widget['description'] : '无描述');
		echo Html::tag('span', array(
			'title'=>'小工具名称',
			'class'=>'fc-grey',
		), ' （'.$widget_instance->title.'）')?>
		<span class="operations"><?php
			echo Html::link('编辑', array('admin/widget/edit', array(
				'id'=>$widget['id'],
			)), array(), true);
			echo Html::link('删除', array('admin/widget/remove-instance', array(
				'id'=>$widget['id'],
			)), array(
				'class'=>'fc-red remove-link',
			), true);
			echo Html::link('复制', array('admin/widget/copy', array(
				'id'=>$widget['id'],
			)), array(), true);
		?></span>
	<?php }?>
	</div>
</div>