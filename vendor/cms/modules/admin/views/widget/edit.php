<?php
use fay\helpers\Html;
?>
<?php echo F::form('widget')->open()?>
<div class="poststuff">
	<div class="post-body">
		<div class="post-body-content"><?php
		if(method_exists($widget_admin, 'index')){ 
			echo $widget_admin->index($widget_data);
		}else{?>
			<div class="box">
				<div class="box-content">该小工具无可配置项</div>
			</div>
		<?php }?></div>
		<div class="postbox-container-1">
			<div class="box">
				<div class="box-title">
					<a class="tools toggle" title="点击以切换"></a>
					<h4>操作</h4>
				</div>
				<div class="box-content">
					<div><?php
						echo F::form('widget')->submitLink('保存', array(
							'class'=>'btn',
						));
						echo Html::link('预览', array('widget/load/'.$widget['alias']), array(
							'class'=>'btn btn-grey ml5',
							'target'=>'_blank',
						));
					?></div>
					<div class="misc-pub-section">
						<strong>是否启用？</strong>
						<?php echo Html::inputRadio('f_widget_enabled', 1, $widget['enabled'] ? true : false, array('label'=>'是'))?>
						<?php echo Html::inputRadio('f_widget_enabled', 0, $widget['enabled'] ? false : true, array('label'=>'否'))?>
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
						<label class="title bold pb0">别名</label>
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
					<div class="form-field">
						<label class="title bold">所属域</label>
						<?php echo Html::select('f_widget_widgetarea', array(''=>'--所属小工具域--')+$widgetareas, $widget['widgetarea'], array(
							'class'=>'form-control',
						))?>
						<p class="fc-grey">小工具可以属于一个域，通过调用域来显示一组小工具</p>
					</div>
					<div class="form-field">
						<label class="title bold pb0">描述</label>
						<?php echo Html::textarea('f_widget_description', $widget['description'], array(
							'class'=>'form-control autosize',
						))?>
					</div>
					<div class="form-field pb0">
						<label class="title bold pb0">是否ajax引入</label>
						<?php echo Html::inputRadio('f_widget_ajax', 1, $widget['ajax'] ? true : false, array('label'=>'是'))?>
						<?php echo Html::inputRadio('f_widget_ajax', 0, $widget['ajax'] ? false : true, array('label'=>'否'))?>
					</div>
					<div class="form-field">
						<label class="title bold pb0">是否缓存</label>
						<?php echo Html::inputRadio('f_widget_cache', 1, $widget['cache'] >= 0 ? true : false, array('label'=>'是'))?>
						<?php echo Html::inputRadio('f_widget_cache', 0, $widget['cache'] < 0 ? true : false, array('label'=>'否'))?>
					</div>
					<div class="form-field <?php if($widget['cache'] < 0)echo 'hide'?>" id="cache-expire-container">
						<label class="title bold pb0">缓存周期</label>
						<?php echo Html::inputText('f_widget_cache_expire', $widget['cache'] >= 0 ? $widget['cache'] : 3600, array(
							'class'=>'form-control w100 ib',
						))?>
						单位（秒）
						<p class="fc-grey">
							0代表不过期
						</p>
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
	common.filebrowserImageUploadUrl = system.url('admin/file/img-upload', {'cat':'widget'});

	$('[name="f_widget_cache"]').on('change', function(){
		if($('[name="f_widget_cache"]:checked').val() == '1'){
			$('#cache-expire-container').show();
		}else{
			$('#cache-expire-container').hide();
		}
	});
});
</script>