<?php
use fay\helpers\Html;
use ncp\helpers\FriendlyLink;
$this->appendCss($this->staticFile('css/food.css'));
?>
<div class="container containerbg">
	<div class="curnav">
		<strong>当前位置：</strong>
		<?php echo Html::link('首页', $this->url())?>
		&gt;
		<span><?php echo $cat['title']?></span>
	</div>
	<!--分类-->
	<div class="nav-switch mt10">
		<div class="nav-category">
			<h4>所在地区：</h4>
			<ul>
				<li><?php echo Html::link('全部', FriendlyLink::getFoodListLink(array(
					'area'=>0,
					'cat'=>$cat_id,
					'month'=>$month_id
				)), array(
					'class'=>$area_id == 0 ? 'sel' : false,
				));?></li>
				<?php foreach($areas as $a){?>
					<li><?php echo Html::link($a['title'], FriendlyLink::getFoodListLink(array(
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
				<li><?php echo Html::link('全部', FriendlyLink::getFoodListLink(array(
					'area'=>$area_id,
					'cat'=>0,
					'month'=>$month_id
				)), array(
					'class'=>$cat_id == 0 ? 'sel' : false,
				))?></li>
				<?php foreach($cats as $c){?>
					<li><?php echo Html::link($c['title'], FriendlyLink::getFoodListLink(array(
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
				<li><?php echo Html::link('全部', FriendlyLink::getFoodListLink(array(
					'area'=>$area_id,
					'cat'=>$cat_id,
					'month'=>0
				)), array(
					'class'=>$month_id == 0 ? 'sel' : false,
				))?></li>
				<?php foreach($monthes as $m){?>
					<li><?php echo Html::link($m['title'], FriendlyLink::getFoodListLink(array(
						'area'=>$area_id,
						'cat'=>$cat_id,
						'month'=>$m['id']
					)), array(
						'class'=>$month_id == $m['id'] ? 'sel' : false,
					))?></li>
				<?php }?>
			</ul>
		</div>
		<div class="nav-search-list nav-category">
			<h4>搜索：</h4>
			<?php
				echo F::form('search')->open(null, 'get');
				echo F::form('search')->inputText('keywords', array(
					'class'=>'J_text_val',
				), '搜索我喜欢吃的');
				echo F::form('search')->submitLink('搜索', array(
					'class'=>'nav-t',
				));
				echo F::form()->close();
			?>
		</div>
	</div>
	<div class="product-all mt10">
		<ul class="product">
			<?php $listview->showData()?>
		</ul>
	</div>
	<?php $listview->showPager(array(
		'type'=>'food_list',
		'params'=>array(
			'area'=>$area_id,
			'cat'=>$cat_id,
			'month'=>$month_id,
		)
	))?>
</div>
<script>
$(function(){
	inputval("搜索我喜欢吃的");
	$(".product li").on("hover",function(){
		$(this).addClass("hover").siblings().removeClass("hover")
	});
});
</script>