<?php
use fay\helpers\Html;
?>
<form id="create-post-form" method="post">
	<input type="hidden" name="status" id="status" />
	<div class="col-2-2">
		<div class="col-2-2-body-sidebar">
			<div class="box">
				<div class="box-title">
					<h4>操作</h4>
				</div>
				<div class="box-content">
					<div>
						<a href="javascript:;" class="btn" id="create-post-form-submit">发布</a>
					</div>
				</div>
			</div>
			<div class="box">
				<div class="box-title">
					<h4>发送时间</h4>
				</div>
				<div class="box-content">
					<?php echo F::form()->inputText('publish_time', array('class'=>'timepicker'))?>
					<div class="color-grey">默认为当前时间</div>
				</div>
			</div>
			<div class="box">
				<div class="box-title">
					<h4>分类</h4>
				</div>
				<div class="box-content">
					<?php echo Html::select('cat_id', Html::getSelectOptions($notification_cats))?>
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
				<div class="mt20">
					<div class="box">
						<div class="box-title">
							<h4>内容</h4>
						</div>
						<div class="box-content">
							<?php echo F::form()->textarea('content', array(
								'class'=>'full-width h60 autosize',
							))?>
						</div>
					</div>
					<div class="box">
						<div class="box-title">
							<h4>群发</h4>
						</div>
						<div class="box-content">
							<div class="wp45 fl">
								<div class="box-2">
									<div class="box-2-title">
										<h5>管理员</h5>
									</div>
									<div class="box-2-content">
									<?php foreach($roles as $r){?>
										<p>
											<?php echo F::form()->inputCheckbox('roles[]', $r['id'], array('label'=>$r['title']));?>
										</p>
									<?php }?>
									</div>
								</div>
							</div>
							<div class="wp45 fr">
								<div class="box-2">
									<div class="box-2-title">
										<h5>用户</h5>
									</div>
									<div class="box-2-content">
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</form>