<section class="section" id="section-banner">
	<div class="bg" style="background-image:url(<?php echo \fay\services\FileService::getUrl($widget->config['file_id'])?>)">
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