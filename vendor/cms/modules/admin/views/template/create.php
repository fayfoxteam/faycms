<?php
use fay\models\tables\Templates;

echo F::form()->open(null, array(), 'post', array('id'=>'form'));
?>
	<div class="col-2-2">
		<div class="col-2-2-body-sidebar" id="side">
			<div class="box" id="box-operation">
				<div class="box-title">
					<a class="tools toggle" title="点击以切换"></a>
					<h4>操作</h4>
				</div>
				<div class="box-content">
					<div>
						<a href="javascript:;" class="btn-1" id="form-submit">提交</a>
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
					<?php echo F::form()->inputText('alias')?>
					<div class="color-grey">别名不可包含特殊字符，可留空。</div>
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
		<div class="col-2-2-body">
			<div class="col-2-2-body-content">
				<div class="titlediv">
					<label class="title-prompt-text" for="title">在此键入标题</label>
					<?php echo F::form()->inputText('title', array(
						'id'=>'title',
						'class'=>'bigtxt',
					))?>
				</div>
				<div class="postarea">
					<?php echo F::form()->textarea('content', array(
						'id'=>'visual-editor',
						'class'=>'h350',
					))?>
				</div>
				<div class="mt20" id="normal">
					<div class="box">
						<div class="box-title">
							<a class="tools toggle" title="点击以切换"></a>
							<h4>模版说明</h4>
						</div>
						<div class="box-content">
							<?php echo F::form()->textarea('description', array(
								'class'=>'full-width h60',
							))?>
						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
<?php echo F::form()->close()?>
<script>
$(function(){
	common.filebrowserImageUploadUrl = system.url("admin/file/upload", {'t':'posts'});
});
</script>