<div class="container page-content">
	<div class="row">
		<main class="col-md-8 post-list">
			<?php F::widget()->load('post-item')?>
		</main>
		<aside class="col-md-4">
			<div class="widget-area">
				<?php F::widget()->area('post-item')?>
			</div>
		</aside>
	</div>
</div>