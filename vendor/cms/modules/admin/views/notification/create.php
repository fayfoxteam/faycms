<?php
use fay\helpers\Html;
?>
<?php echo F::form()->open()?>
<div class="poststuff">
	<div class="post-body">
		<div class="postbox-container-1">
			<div class="box">
				<div class="box-title">
					<h4>操作</h4>
				</div>
				<div class="box-content">
					<div>
						<?php echo F::form()->submitLink('发送', array(
							'class'=>'btn',
						))?>
					</div>
				</div>
			</div>
			<div class="box">
				<div class="box-title">
					<h4>发送时间</h4>
				</div>
				<div class="box-content">
					<?php echo F::form()->inputText('publish_time', array(
						'class'=>'form-control timepicker',
					))?>
					<div class="fc-grey">默认为当前时间</div>
				</div>
			</div>
			<div class="box">
				<div class="box-title">
					<h4>分类</h4>
				</div>
				<div class="box-content">
					<?php echo Html::select('cat_id', Html::getSelectOptions($notification_cats), '', array(
						'class'=>'form-control',
					))?>
				</div>
			</div>
		</div>
		<div class="postbox-container-2">
			<div class="mb30">
				<?php echo F::form()->inputText('title', array(
					'id'=>'title',
					'class'=>'form-control bigtxt',
					'placeholder'=>'在此键入标题',
				))?>
			</div>
			<div class="mt20">
				<div class="box">
					<div class="box-title">
						<h4>内容</h4>
					</div>
					<div class="box-content">
						<?php echo F::form()->textarea('content', array(
							'class'=>'form-control h90 autosize',
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
	</div>
</div>
<?php echo F::form()->close()?>