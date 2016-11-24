<section class="section" id="section-banner">
	<div class="bg" style="background-image:url(<?php echo $this->appStatic('images/HPLC_bd5djg.jpg')?>)">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php F::widget()->area('index-banner')?>
				</div>
			</div>
		</div>
		<div class="arrow">
			<div class="a1"></div>
			<div class="a2"></div>
		</div>
	</div>
</section>
<section class="section" id="section-products">
	<?php F::widget()->load('product-list')?>
</section>
<section class="section" id="section-contact">
	<div class="bg">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2 class="title">Client Inquiry Form</h2>
					<div class="description">
						<p>Don't hesitate to leave us a message if you have any further interests and questions by using the contact form.</p>
						<p>We do take care of privacy, your email address will not be published. If you would love to get a faster reply, please use the contact app listed below. And Do please use ONLY the listed contact information here and all orders will be processed by these methods, too. You might need to read F.A.Q page, it will make your life easier.</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<form class="contact-form" id="contact-form" action="<?php echo $this->url('contact/send')?>" method="post">
						<fieldset>
							<input name="name" placeholder="Your Name" />
						</fieldset>
						<fieldset>
							<input name="email" placeholder="Your Email" />
						</fieldset>
						<fieldset>
							<input name="phone" placeholder="Your Phone" />
						</fieldset>
						<fieldset>
							<textarea name="message" placeholder="Message: It's highly appreciated to inquiry with product names, quantities and your country name, thanks."></textarea>
						</fieldset>
						<fieldset>
							<a href="javascript:;" class="btn btn-transparent" id="contact-form-submit">SUBMIT</a>
						</fieldset>
					</form>
				</div>
				<div class="col-md-4">
					<?php F::widget()->load('baidu-map')?>
				</div>
				<div class="col-md-4">
					<div class="contact-info">
						<div class="contact-info-item">
							<i class="fa fa-map-marker"></i>
							<div class="detail">Buji, Shenzhen, China</div>
						</div>
						<div class="contact-info-item">
							<i class="fa fa-clock-o"></i>
							<div class="detail">
								Orders on Sunday are sent on Monday.
								<br>
								<br>
								Rina 24*7 whatsapp: 8618038026406
								<br>
								Email: <a href="mailto:admin@fayfox.com">admin@fayfox.com</a>
								<br>
								<br>
								David 24*7 whatsapp: 8618038192037
								<br>
								Skype: lee.liangqing1
								<br>
								Wickr: davidpharmade
							</div>
						</div>
						<div class="contact-info-item">
							<i class="fa fa-mobile"></i>
							<div class="detail">86-18038192037</div>
						</div>
						<div class="contact-info-item">
							<i class="fa fa-envelope-o"></i>
							<div class="detail"><a href="mailto:admin@fayfox.com">admin@fayfox.com</a></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="section" id="section-ancillary">
	<?php F::widget()->load('ancillary-list')?>
</section>
<section class="section" id="section-blog">
	<?php F::widget()->load('blog-list')?>
</section>
<section class="section" id="section-faq">
	<div class="bg" style="background-image:url(<?php echo $this->appStatic('images/120.jpg')?>)">
		<?php F::widget()->load('faq-list')?>
	</div>
</section>
<script>
	var contact = {
		'validform': function(rules, labels){
			system.getScript(system.assets('faycms/js/fayfox.validform.js'), function(){
				$('#contact-form').validform({
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