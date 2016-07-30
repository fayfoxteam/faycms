<?php
use fay\helpers\Html;
?>
<div class="contact-map">
	<?php F::widget()->load('contact-map');?>
</div>
<div class="container page-content contact">
	<div class="row">
		<div class="col-md-12">
			<div class="contact-page">
				<h2><?php echo Html::encode($page['title'])?></h2>
				<div class="content">
					<?php echo $page['content']?>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<div class="contact-info">
				<?php F::widget()->load('contact-info')?>
			</div>
		</div>
		<div class="col-sm-8">
			<form class="contact-form">
				<fieldset>
					<input name="name" placeholder="称呼" />
				</fieldset>
				<fieldset>
					<input name="email" placeholder="邮箱" />
				</fieldset>
				<fieldset>
					<textarea name="message" placeholder="消息"></textarea>
				</fieldset>
			</form>
		</div>
	</div>
</div>