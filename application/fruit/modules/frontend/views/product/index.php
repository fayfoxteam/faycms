<?php
use fay\helpers\Html;
?>
<div class="g-con">
	<div class="g-mn">
		<h1 class="sec-title"><span>产品中心</span></h1>
		<h3 class="sub-title">分类</h3>
		<div class="clearfix cat-list">
			<ul>
				<li><?php echo Html::link('全部', array(
					'product'
				), array(
					'class'=>($cat['alias'] == 'product') ? 'crt' : false,
				))?></li>
			<?php foreach($cats as $c){?>
				<li><?php echo Html::link($c['title'], array(
					'product/'.$c['alias']
				), array(
					'class'=>($cat['alias'] == $c['alias']) ? 'crt' : false,
				))?></li>
			<?php }?>
			</ul>
		</div>
		<div class="product-list">
			<ul class="clearfix">
				<?php $listview->showData();?>
			</ul>
			<?php $listview->showPager();?>
		</div>
	</div>
</div>