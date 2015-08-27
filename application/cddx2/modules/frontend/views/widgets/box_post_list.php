<?php
use fay\helpers\Html;
?>
<div class="box">
	<div class="box-title">
		<h3>新闻中心</h3>
		<em>News</em>
		<?php echo Html::link('more..', '', array(
			'class'=>'more-link',
		))?>
	</div>
	<div class="box-content">
		<ul class="box-post-list">
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