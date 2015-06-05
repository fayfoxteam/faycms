<?php
use fay\helpers\Html;
?>
<div class="g-con">
	<div class="g-mn clearfix">
		<div class="g-mnc">
			<section class="news-list"><?php
				$listview->showData();
				$listview->showPager();
			?></section>
		</div>
		<div class="g-aside">
			<aside class="box">
				<div class="box-title">
					<h3 class="sub-title">新闻分类</h3>
				</div>
				<div class="box-content">
					<ul class="box-cats">
					<?php foreach($cats as $c){?>
						<li><?php echo Html::link($c['title'], array(
							'news/'.$c['alias']
						))?></li>
					<?php }?>
					</ul>
				</div>
			</aside>
		</div>
	</div>
</div>