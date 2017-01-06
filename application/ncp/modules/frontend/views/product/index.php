<?php
use fay\helpers\HtmlHelper;
use ncp\helpers\FriendlyLink;
$this->appendCss($this->appStatic('css/product.css'));
?>
<div class="container containerbg">
	<div class="curnav">
		<strong>当前位置：</strong>
		<?php echo HtmlHelper::link('首页', $this->url())?>
		&gt;
		<span><?php echo $cat['title']?></span>
	</div>
	<!--分类-->
	<div class="nav-switch mt10">
		<div class="nav-category">
			<h4>所在地区：</h4>
			<ul>
				<li><?php echo HtmlHelper::link('全部', FriendlyLink::getProductListLink(array(
					'area'=>0,
					'cat'=>$cat_id,
					'month'=>$month_id
				)), array(
					'class'=>$area_id == 0 ? 'sel' : false,
				));?></li>
				<?php foreach($areas as $a){?>
					<li><?php echo HtmlHelper::link($a['title'], FriendlyLink::getProductListLink(array(
						'area'=>$a['id'],
						'cat'=>$cat_id,
						'month'=>$month_id
					)), array(
						'class'=>$area_id == $a['id'] ? 'sel' : false,
					));?></li>
				<?php }?>
			</ul>
		</div>
		<div class="nav-category">
			<h4>所有分类：</h4>
			<ul>
				<li><?php echo HtmlHelper::link('全部', FriendlyLink::getProductListLink(array(
					'area'=>$area_id,
					'cat'=>0,
					'month'=>$month_id
				)), array(
					'class'=>$cat_id == 0 ? 'sel' : false,
				))?></li>
				<?php foreach($cats as $c){?>
					<li><?php echo HtmlHelper::link($c['title'], FriendlyLink::getProductListLink(array(
						'area'=>$area_id,
						'cat'=>$c['id'],
						'month'=>$month_id
					)), array(
						'class'=>$cat_id == $c['id'] ? 'sel' : false,
					))?></li>
				<?php }?>
			</ul>
		</div>
		<div class="nav-category">
			<h4>产出月份：</h4>
			<ul>
				<li><?php echo HtmlHelper::link('全部', FriendlyLink::getProductListLink(array(
					'area'=>$area_id,
					'cat'=>$cat_id,
					'month'=>0
				)), array(
					'class'=>$month_id == 0 ? 'sel' : false,
				))?></li>
				<?php foreach($monthes as $m){?>
					<li><?php echo HtmlHelper::link($m['title'], FriendlyLink::getProductListLink(array(
						'area'=>$area_id,
						'cat'=>$cat_id,
						'month'=>$m['id']
					)), array(
						'class'=>$month_id == $m['id'] ? 'sel' : false,
					))?></li>
				<?php }?>
			</ul>
		</div>
	</div>
	<div class="product-all mt10">
		<ul class="product">
			<?php $listview->showData()?>
		</ul>
	</div>
	<?php $listview->showPager(array(
		'type'=>'product_list',
		'params'=>array(
			'area'=>$area_id,
			'cat'=>$cat_id,
			'month'=>$month_id,
		)
	))?>
</div>
