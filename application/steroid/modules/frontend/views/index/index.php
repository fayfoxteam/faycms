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
					<?php F::widget()->load('w584b9590ec91b')?>
				</div>
				<div class="col-md-4">
					<?php F::widget()->load('baidu-map')?>
				</div>
				<div class="col-md-4">
					<?php F::widget()->load('contact-info')?>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="section" id="section-ancillary">
	<?php F::widget()->load('ancillary-list')?>
</section>
<section class="section" id="section-product-link">
	<div class="bg" style="background-image:url(<?php echo $this->appStatic('images/148.jpg')?>)">
		<div class="container">
			<div class="row">
				<div class="col-md-7">
					<div class="title-group">
						<h2 class="title">Full Prices of Steroid Oils</h2>
						<div class="description">
							<?php $post = \fay\models\tables\Posts::model()->fetchRow(
								\fay\models\tables\Posts::getPublishedConditions(),
								'publish_time',
								'publish_time DESC'
							)?>
							<p>Last update <?php echo date('F j, Y', $post['publish_time'])?></p>
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="product-link-container">
						<a href="<?php echo $this->url('post')?>" class="btn btn-transparent">PRODUCT LIST</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="section" id="section-blog">
	<?php F::widget()->load('blog-list')?>
</section>
<section class="section" id="section-faq">
	<div class="bg" style="background-image:url(<?php echo $this->appStatic('images/120.jpg')?>)">
		<?php F::widget()->load('faq-list')?>
	</div>
</section>