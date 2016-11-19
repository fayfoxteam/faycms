<?php
use fay\helpers\Html;

$widget = F::widget()->get($data['widget_name'], true);
?>
<tr valign="top">
	<td>
		<strong><?php
			echo $data['description'] ? Html::encode($data['description']) : '&nbsp';
		?></strong>
		<div class="row-actions"><?php
			echo Html::link('编辑', array('admin/widget/edit', array(
				'id'=>$data['id'],
			)), array(), true);
			echo Html::link('删除', array('admin/widget/remove-instance', array(
				'id'=>$data['id'],
			)), array(
				'class'=>'fc-red remove-link',
			), true);
		?></div>
	</td>
	<td><?php echo $data['alias']?></td>
	<td><?php if($data['enabled']){
		echo '<span class="fc-green">是</span>';
	}else{
		echo '<span class="fc-orange">否</span>';
	}?></td>
	<td><?php if($widget == null){
		echo '<span class="fc-red">小工具已被移除</span>';
	}else{
		echo $widget->title;
	}?></td>
	<td><?php echo $data['widget_name']?></td>
</tr>