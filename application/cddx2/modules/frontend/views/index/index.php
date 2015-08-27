<?php
use fay\helpers\Html;
?>
<div class="w1000">
	<section id="section-1">
		<div class="box">
			<div class="box-title">
				<h3>新闻中心</h3>
				<em>News</em>
				<?php echo Html::link('more..', '', array(
					'class'=>'more-link',
				))?>
			</div>
			<div class="box-content">
				<div class="box-top-news">
					<h4><?php echo Html::link('弘扬延安精神  奋力谱写中国梦成都篇章')?></h4>
					<p>
						2月27日，成都市委党校召开了“弘扬延安精神 奋力谱写中国梦”成都篇章专题教育活动，全校师生共1005人参加...
						<?php echo Html::link('[详情]')?>
					</p>
				</div>
				<ul class="box-news-list">
					<?php echo Html::link('<span>标题</span>', array(), array(
						'title'=>'标题',
						'encode'=>false,
						'wrapper'=>'li',
						'append'=>array(
							'tag'=>'time',
							'text'=>'(2015-08-27)',
						),
					))?>
					<?php echo Html::link('<span>标题</span>', array(), array(
						'title'=>'标题',
						'encode'=>false,
						'wrapper'=>'li',
						'append'=>array(
							'tag'=>'time',
							'text'=>'(2015-08-27)',
						),
					))?>
					<?php echo Html::link('<span>标题</span>', array(), array(
						'title'=>'标题',
						'encode'=>false,
						'wrapper'=>'li',
						'append'=>array(
							'tag'=>'time',
							'text'=>'(2015-08-27)',
						),
					))?>
					<?php echo Html::link('<span>标题</span>', array(), array(
						'title'=>'标题',
						'encode'=>false,
						'wrapper'=>'li',
						'append'=>array(
							'tag'=>'time',
							'text'=>'(2015-08-27)',
						),
					))?>
				</ul>
			</div>
		</div>
	</section>
</div>