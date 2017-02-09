<div class="row">
	<div class="col-12">
		<div class="tabbable">
			<ul class="nav-tabs">
				<li class="active"><a href="#settings-options">站点参数</a></li>
				<li><a href="#settings-system">系统参数</a></li>
				<li><a href="#settings-email">邮箱参数</a></li>
				<li><a href="#settings-qiniu">七牛参数</a></li>
				<li><a href="#settings-ucpaas">云之讯参数</a></li>
			</ul>
			<div class="tab-content">
				<div id="settings-options" class="tab-pane p5">
					<?php $this->renderPartial('_settings_options')?>
				</div>
				<div id="settings-system" class="tab-pane p5 hide">
					<?php $this->renderPartial('_settings_system')?>
				</div>
				<div id="settings-email" class="tab-pane p5 hide">
					<?php $this->renderPartial('_settings_email')?>
				</div>
				<div id="settings-qiniu" class="tab-pane p5 hide">
					<?php $this->renderPartial('_settings_qiniu')?>
				</div>
				<div id="settings-ucpaas" class="tab-pane p5 hide">
					<?php $this->renderPartial('_settings_ucpaas')?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->renderPartial('_form_js')?>
<script>
$(function(){
	//logo上传
	system.getScript(system.assets('faycms/js/admin/uploader.js'), function(){
		uploader.image({
			'browse_button': 'upload-logo',
			'container': 'logo-container',
			'preview_container': 'logo-preview-container',
			'input_name': 'site:logo',
			'remove_link_text': '移除Logo',
			'preview_image_params': {
				't': 1
			}
		});
	});
});
</script>