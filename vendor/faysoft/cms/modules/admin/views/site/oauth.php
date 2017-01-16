<div class="row">
	<div class="col-12">
		<div class="tabbable">
			<ul class="nav-tabs">
				<li class="active"><a href="#options-panel">微信登录</a></li>
			</ul>
			<div class="tab-content">
				<div id="options-panel" class="tab-pane p5">
					<?php $this->renderPartial('_oauth_weixin')?>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(function(){
	system.getCss(system.assets('css/tip-twitter/tip-twitter.css'));
	system.getScript(system.assets('js/jquery.poshytip.min.js'));
	
	system.getScript(system.assets('faycms/js/fayfox.validform.min.js'), function(){
		$('.site-settings-form').validform({
			'ajaxSubmit':true,
			'onAjaxEnd':function(obj, resp){
				if(!resp.status){
					$('body').unblock();
				}
			},
			'beforeSubmit':function(){
				$('body').block({
					'zindex':1300
				});
			},
			'onError':function(obj, msg, rule){
				$('body').unblock();
				var last = $.validform.getElementsByName(obj).last();
				last.poshytip('destroy');
				//报错
				last.poshytip({
					'className': 'tip-twitter',
					'showOn': 'none',
					'alignTo': 'target',
					'alignX': 'inner-right',
					'offsetX': -60,
					'offsetY': 5,
					'content': msg
				}).poshytip('show');
			},
			'onSuccess':function(obj){
				var last = $.validform.getElementsByName(obj).last();
				last.poshytip('destroy');
			},
			'afterAjaxSubmit':function(resp){
				$('body').unblock();
				common.notify('保存成功', 'success');
			}
		});
	});
});
</script>