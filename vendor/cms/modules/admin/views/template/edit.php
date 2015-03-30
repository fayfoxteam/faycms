<?php
use fay\models\tables\Templates;

echo F::form()->open();
?>
<div class="poststuff">
	<div class="post-body">
		<div class="post-body-content">
			<div class="post-title-env">
				<?php echo F::form()->inputText('title', array(
					'id'=>'title',
					'class'=>'form-control bigtxt',
					'placeholder'=>'在此键入标题',
				))?>
			</div>
			<div class="postarea">
				<?php echo F::form()->textarea('content', array(
					'id'=>'visual-editor',
					'class'=>'h350',
				))?>
			</div>
		</div>
		<div class="postbox-container-1 dragsort">
			<div class="box" id="box-operation">
				<div class="box-title">
					<a class="tools toggle" title="点击以切换"></a>
					<h4>操作</h4>
				</div>
				<div class="box-content">
					<div>
						<?php echo F::form()->submitLink('更新', array(
							'class'=>'btn',
						))?>
					</div>
					<div class="misc-pub-section">
						<strong>启用</strong>
						<?php echo F::form()->inputRadio('enable', 1, array('label'=>'是'), true)?>
						<?php echo F::form()->inputRadio('enable', 0, array('label'=>'否'))?>
					</div>
				</div>
			</div>
			<div class="box" id="box-alias">
				<div class="box-title">
					<a class="tools toggle" title="点击以切换"></a>
					<h4>别名</h4>
				</div>
				<div class="box-content">
					<?php echo F::form()->inputText('alias', array(
						'class'=>'form-control',
					))?>
					<div class="fc-grey">别名不可包含特殊字符，可留空。</div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="box" id="box-type">
				<div class="box-title">
					<a class="tools toggle" title="点击以切换"></a>
					<h4>类型</h4>
				</div>
				<div class="box-content">
					<p><?php echo F::form()->inputRadio('type', Templates::TYPE_EMAIL, array(
						'label'=>'邮件',
					), true)?></p>
					<p><?php echo F::form()->inputRadio('type', Templates::TYPE_SMS, array(
						'label'=>'短信',
					))?></p>
					<p><?php echo F::form()->inputRadio('type', Templates::TYPE_NOTIFICATION, array(
						'label'=>'站内信',
					))?></p>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<div class="postbox-container-2 dragsort">
			<div class="box">
				<div class="box-title">
					<a class="tools toggle" title="点击以切换"></a>
					<h4>模版说明</h4>
				</div>
				<div class="box-content">
					<?php echo F::form()->textarea('description', array(
						'class'=>'form-control h90',
					))?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo F::form()->close()?>
<script>
$(function(){
	common.filebrowserImageUploadUrl = system.url("admin/file/upload", {'t':'posts'});
});
</script>