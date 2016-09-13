<div class="container page-content">
	<div class="row">
		<main class="col-md-8 post-list">
			<?php F::widget()->load('post-list')?>
		</main>
		<aside class="col-md-4">
			<div class="widget-area">
				<?php F::widget()->area('index-sidebar')?>
				<div class="widget">
					<h5 class="widget-title">热门文章</h5>
					<article>
						<div class="post-thumb">
							<a href=""><img src="http://55.fayfox.com/fayfox/file/pic/f/10000?t=4&dw=60&dh=60" /></a>
						</div>
						<div class="post-container">
							<h5 class="post-title">
								<a href="">这是一个文章标题</a>
							</h5>
							<div class="post-meta">
								<span class="post-meta-category">分类1</span>
								<time class="post-meta-time">3天前</time>
							</div>
						</div>
					</article>
					<article>
						<div class="post-thumb">
							<a href=""><img src="http://55.fayfox.com/fayfox/file/pic/f/10000?t=4&dw=60&dh=60" /></a>
						</div>
						<div class="post-container">
							<h5 class="post-title">
								<a href="">这是一个文章标题</a>
							</h5>
							<div class="post-meta">
								<span class="post-meta-category">分类1</span>
								<time class="post-meta-time">3天前</time>
							</div>
						</div>
					</article>
				</div>
			</div>
		</aside>
	</div>
</div>