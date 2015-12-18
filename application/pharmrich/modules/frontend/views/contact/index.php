<div class="container">
	<div class="g-mn">
		<h1 class="sec-title"><span>联系我们</span></h1>
		
		<?php F::widget()->load('contact-map')?>
		
		<div id="contact-page" class="clearfix">
			<?php echo $page['content']?>
		</div>
		
		<div>
			<h3 class="sub-title">给我们发邮件</h3>
			<form method="post" action="<?php echo $this->url('contact/markmessage')?>" id="leave-message-form" class="validform">
				<fieldset>
					<div class="one-third fl">
						<label>称呼</label>
						<input type="text" name="name" />
					</div>
					<div class="one-third fl">
						<label>电话</label>
						<input type="text" name="phone" />
					</div>
					<div class="one-third fl">
						<label>邮箱</label>
						<input type="text" name="email" />
					</div>
					<div class="clear">
						<label>留言内容</label>
						<textarea name="message"></textarea>
					</div>
					<a href="javascript:;" class="send fr" id="leave-message-form-submit">发送邮件</a>
				</fieldset>
			</form>
		</div>
	</div>
</div>