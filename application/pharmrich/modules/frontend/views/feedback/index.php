<div class="centered-wrapper cf mt30">
	<section class="g-mn">
		<ul><?php $listview->showData()?></ul>
		<?php echo $listview->showPager()?>
		<form method="post" action="<?php echo $this->url('contact/send')?>" id="leave-message-form" class="validform">
			<fieldset>
				<div class="one-third fl">
					<label>Name</label>
					<input type="text" name="name" />
				</div>
				<div class="one-third fl">
					<label>Email</label>
					<input type="text" name="email" />
				</div>
				<div class="one-third fl">
					<label>Subject</label>
					<input type="text" name="subject" />
				</div>
				<div class="cf">
					<label>Your Message</label>
					<textarea name="message"></textarea>
				</div>
				<a href="javascript:;" class="send fr" id="leave-message-form-submit">Send Message</a>
			</fieldset>
		</form>
	</section>
	<aside class="g-sd"><?php F::widget()->area('feedback-sidebar')?></aside>
</div>

<script>
var contact = {
	'toast':function(message, type){
		type = type || 'success';
		system.getScript(system.assets('faycms/js/fayfox.toast.js'), function(){
			if(type == 'success'){
				//成功的提醒5秒后自动消失，不出现关闭按钮，点击则直接消失
				$.toast(message, type, {
					'closeButton': false,
					'positionClass': 'toast-bottom-middle',
					'click': function(message){
						message.fadeOut();
					}
				});
			}else if(type == 'error'){
				//单页报错，在底部中间出现，红色背景，不显示关闭按钮，点击消失，延迟5秒消失
				$.toast(message, type, {
					'closeButton': false,
					'positionClass': 'toast-bottom-middle',
					'click': function(message){
						message.fadeOut();
					}
				});
			}else{
				//其它类型，点击关闭消失，不自动消失
				$.toast(message, type, {
					'timeOut': 0,
					'positionClass': 'toast-bottom-middle'
				});
			}
		});
	},
	'validform': function(rules, labels){
		system.getScript(system.assets('faycms/js/fayfox.validform.js'), function(){
			$('#leave-message-form').validform({
				'showAllErrors': false,
				'onError': function(obj, msg, rule){
					contact.toast(msg, 'error');
				},
				'ajaxSubmit': true,
				'afterAjaxSubmit': function(resp){
					if(resp.status){
						contact.toast('Message has been send', 'success');
					}else{
						contact.alert(resp.message);
					}
				}
			}, rules, labels);
		});
	},
	'events': function(){
		$('#leave-message-form-submit').on('click', function(){
			$('#leave-message-form').submit();
		});
	}
};
$(function(){
	contact.validform(<?php echo json_encode(F::form()->getJsRules())?>, <?php echo json_encode(F::form()->getLabels())?>);
	contact.events();
});
</script>