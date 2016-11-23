<div class="container page-content">
	<div class="row">
		<main class="col-md-8 post-list">
			<?php F::widget()->load('post-list')?>
		</main>
		<aside class="col-md-4">
			<div class="widget-area">
				<?php F::widget()->area('post-list-sidebar')?>
			</div>
		</aside>
	</div>
</div>