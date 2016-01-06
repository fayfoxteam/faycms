<div class="centered-wrapper cf mt30">
	<section class="g-mn">
		<ul><?php $listview->showData()?></ul>
		<?php echo $listview->showPager()?>
		<form method="post" action="<?php echo $this->url('contact/send')?>" id="leave-message-form" class="validform">
			<h3>Leave your message</h3>
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
	'validform': function(rules, labels){
		system.getScript(system.assets('faycms/js/fayfox.validform.js'), function(){
			$('#leave-message-form').validform({
				'showAllErrors': false,
				'onError': function(obj, msg, rule){
					common.toast(msg, 'error');
				},
				'ajaxSubmit': true,
				'afterAjaxSubmit': function(resp){
					if(resp.status){
						common.toast('Message has been send', 'success');
					}else{
						common.toast(resp.message, 'error');
					}
				}
			}, rules, labels);
		});
	}
};
$(function(){
	contact.validform(<?php echo json_encode(F::form()->getJsRules())?>, <?php echo json_encode(F::form()->getLabels())?>);
});
</script>