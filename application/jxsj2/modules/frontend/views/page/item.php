<?php
use fay\helpers\HtmlHelper;
?>
<div id="banner">
	<?php \F::widget()->load('index-slides')?>
</div>
<div class="w1000 clearfix bg-white">
	<div class="w230 fl">
		<?php //$this->renderPartial('common/_login_panel')?>
		<?php \F::widget()->load('newest-posts')?>
		<?php \F::widget()->load('friendlinks')?>
	</div>
	<div class="ml240">
		<div class="box" id="post-item">
			<div class="box-title">
				<h3></h3>
			</div>
			<div class="box-content">
				<div class="st"><div class="sl"><div class="sr"><div class="sb">
					<div class="p16">
						<h1><?php echo HtmlHelper::encode($page['title'])?></h1>
						<div class="meta">
							<span class="ml10">阅读数：<?php echo $page['views']?></span>
						</div>
						<div class="post-content">
							<?php echo $page['content']?>
						</div>
					</div>
				</div></div></div></div>
			</div>
		</div>
	</div>
</div>
