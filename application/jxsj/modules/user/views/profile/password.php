<div class="box fl wp100">
	<div class="box-title">
		<h3>参加考试</h3>
	</div>
	<div class="box-content">
		<div class="st"><div class="sl"><div class="sr"><div class="sb">
			<div class="p16 clearfix">
				<form class="validform" method="post" id="form">
					<section class="box-4 clearfix">
						<div class="content fl mr49">
							<div class="form-field-2">
								<label class="title-prompt-text">原密码</label>
								<?php echo F::form()->inputPassword('old_password', array(
									'class'=>'inputxt long',
									'datatype'=>'*',
									'nullmsg'=>'密码不能为空',
								))?>
							</div>
							<div class="form-field-2">
								<label class="title-prompt-text">新密码</label>
								<?php echo F::form()->inputPassword('password', array(
									'class'=>'inputxt long',
									'id'=>'password',
									'datatype'=>'*',
									'nullmsg'=>'密码不能为空',
								))?>
							</div>
							<div class="form-field-2">
								<label class="title-prompt-text">确认新密码</label>
								<?php echo F::form()->inputPassword('repassword', array(
									'class'=>'inputxt long',
									'id'=>'repassword',
									'recheck'=>'password',
									'datatype'=>'*',
									'nullmsg'=>'确认密码不能为空',
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