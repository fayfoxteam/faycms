<?php
/**
 * @var $this \fay\core\View
 * @var $listview \fay\common\ListView
 */
?>
<header class="page-header">
	<div class="header-content">
		<span class="header-logo"><img src="<?php echo $this->appAssets('images/forum/logo.png')?>"></span>
		<span class="header-title">公民学者</span>
		<span class="header-subtitle">你就是文化学者</span>
	</div>
</header>
<div class="main-content">
	<div class="messages">
		<?php $listview->showData()?>
	</div>
	<div class="message-pager">
		<?php $listview->showPager()?>
	</div>
	<div class="reply-container">
		<fieldset>
			<label>识&nbsp;别&nbsp;号</label>
			<input type="text" readonly="readonly" value="<?php echo \fay\helpers\HtmlHelper::encode($user['mobile'])?>">
		</fieldset>
		<fieldset>
			<label>军团代号</label>
			<input type="text" readonly="readonly" value="<?php echo \guangong\helpers\UserHelper::getCode(\F::app()->current_user)?>">
		</fieldset>
		<form id="reply-form">
			<input type="hidden" name="type" value="<?php echo \guangong\models\tables\GuangongMessagesTable::TYPE_BINGJIAN?>">
			<textarea name="content" placeholder="作为一个历史爱好者、文化搜索者，五千年文化泱泱中华之子民，总有一座古城、一种文化、一段历史触动你的心弦。既然来了就说点什么吧！三言两语不嫌少，千言万语不嫌多。"></textarea>
			<div class="submit-container">
				<a href="javascript:;" id="reply-form-submit">提&nbsp;&nbsp;交</a>
			</div>
		</form>
	</div>
</div>
<script>
var forum = {
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
					'timeOut': 5000,
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
	}
};
$(function(){
	$('#reply-form-submit').on('click', function(){
		if(!$('#reply-form textarea').val()){
			forum.toast('留言内容不能为空', 'error');
			return false;
		}
		$.ajax({
			'type': 'POST',
			'url': system.url('api/message/create'),
			'data': $('#reply-form').serialize(),
			'dataType': 'json',
			'cache': false,
			'success': function(resp){
				if(resp.status){
					window.location.reload();
				}else{
					forum.toast(resp.message, 'error');
				}
			}
		});
	});
});
</script>