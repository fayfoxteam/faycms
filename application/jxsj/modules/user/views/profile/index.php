<div class="box fl wp100">
	<div class="box-title">
		<h3>参加考试</h3>
	</div>
	<div class="box-content">
		<div class="st"><div class="sl"><div class="sr"><div class="sb">
			<div class="p16 clearfix">
				<form class="validform" method="post" id="form">
					<section class="clearfix box-4">
						<div class="content fl mr49">
							<div class="form-field-2">
								<label class="title-prompt-text">登录名</label>
								<?php echo F::form()->inputText('username', array(
									'class'=>'inputxt long',
									'disabled'=>'disabled',
									'title'=>'登录名不能修改',
								))?>
							</div>
							<div class="form-field-2">
								<label class="title-prompt-text">电子邮箱</label>
								<?php echo F::form()->inputText('email', array(
									'datatype'=>'e',
									'errormsg'=>'电子邮箱格式不正确',
									'nullmsg'=>'电子邮箱不能为空',
									'class'=>'inputxt long',
								))?>
							</div>
							<div class="form-field-2">
								<label class="title-prompt-text">联系电话</label>
								<?php echo F::form()->inputText('cellphone', array(
									'datatype'=>'m',
									'errormsg'=>'手机格式不正确',
									'class'=>'inputxt long',
									'ignore'=>'ignore',
								))?>
							</div>
							<div class="form-field-2">
								<label class="title-prompt-text">昵称</label>
								<?php echo F::form()->inputText('nickname', array(
									'datatype'=>'*1-30',
									'errormsg'=>'姓名不能超过30个字符',
									'class'=>'inputxt long',
									'ignore'=>'ignore',
								))?>
							</div>
							<div class="form-field-2">
								<label class="title-prompt-text">姓名</label>
								<?php echo F::form()->inputText('realname', array(
									'datatype'=>'*1-30',
									'errormsg'=>'姓名不能超过30个字符',
									'class'=>'inputxt long',
									'ignore'=>'ignore',
								))?>
							</div>
						</div>
					</section>
					<a href="javascript:;" class="btn-2" id="form-submit">确认提交</a>
				</form>
			</div>
		</div></div></div></div>
	</div>
</div>
<script>
$(function(){
	system.getCss(system.url('css/tip-twitter/tip-twitter.css'));
	system.getScript(system.url('js/jquery.poshytip.min.js'), function(){
		$("input[name='username']").poshytip("enable").poshytip("show");
	});
});
</script>