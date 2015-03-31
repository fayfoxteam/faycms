<?php
use fay\helpers\Html;
?>
<?php echo F::form('widget')->open()?>
<div class="poststuff">
	<div class="post-body">
		<div class="post-body-content">
			<?php echo $widget_admin->index($widget_data);?>
		</div>
		<div class="postbox-container-1">
			<div class="box">
				<div class="box-title">
					<a class="tools toggle" title="点击以切换"></a>
					<h4>操作</h4>
				</div>
				<div class="box-content">
					<div><?php echo F::form('widget')->submitLink('保存', array(
						'class'=>'btn',
					))?></div>
					<div class="misc-pub-section">
						<strong>是否启用？</strong>
						<?php echo Html::inputRadio('f_widget_enabled', 1, ($widget['enabled']) ? true : false, array('label'=>'是'))?>
						<?php echo Html::inputRadio('f_widget_enabled', 0, ($widget['enabled']) ? false : true, array('label'=>'否'))?>
						<p class="fc-grey">停用后不再显示，但会保留设置</p>
					</div>
				</div>
			</div>
			<div class="box">
				<div class="box-title">
					<a class="tools toggle" title="点击以切换"></a>
					<h4>小工具信息</h4>
				</div>
				<div class="box-content">
					<div class="form-field pb0 pt0">
						<label class="title pb0">别名</label>
						<?php echo Html::inputText('f_widget_alias', $widget['alias'], array(
							'data-rule'=>'string',
							'data-label'=>'别名',
							'data-params'=>'{max:255,format:\'alias\'}',
							'data-ajax'=>$this->url('admin/widget/is-alias-not-exist', array('id'=>$widget['id'])),
							'class'=>'form-control',
						))?>
						<p class="fc-grey">
							唯一的识别一个widget实例
						</p>
					</div>
					<div class="form-field pb0">
						<label class="title pb0">描述</label>
						<?php echo Html::textarea('f_widget_description', $widget['description'], array(
							'class'=>'form-control autosize',
						))?>
					</div>
				</div>
			</div>
			<?php if(method_exists($widget_admin, 'sidebar')){
				$widget_admin->sidebar($widget_data);
			}?>
		</div>
	</div>
</div>
<?php echo F::form('widget')->close()?>
<script>
$(function(){
	common.filebrowserImageUploadUrl = system.url("admin/file/upload", {'t':'widget-<?php echo $widget['alias']?>'});
});
</script>