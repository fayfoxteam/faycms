<?php
use fay\helpers\Html;
use fay\models\File;
use fay\helpers\String;
?>
<section class="sec-1 mb30">
	<div class="container">
		<div class="row cols col3 cf">
			<h2 class="sub-title">ABOUT US</h2>
			<div class="box">
				<h3 class="box-title">
					<span>勾勒画室</span>
				</h3>
				<div class="box-content">
					<?php echo String::nl2p(Html::encode($about['abstract']))?>
					<?php echo Html::link('+MORE', array('about'), array(
						'class'=>'more-link',
					))?>
				</div>
			</div>
			<div class="box" id="advantage">
				<h3 class="box-title">
					<span>勾勒优势</span>
				</h3>
				<div class="box-content">
					<ul><?php F::widget()->load('index-advantage')?></ul>
				</div>
			</div>
			<div class="box" id="environment">
				<h3 class="box-title">
					<span>勾勒画室</span>
				</h3>
				<div class="box-content">
					<ul><?php F::widget()->load('environment')?></ul>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="sec-1 mb30 teachers">
	<div class="container cf">
		<div class="teacher-list-container">
			<h2 class="en">teacher strength</h2>
			<h2>师资力量</h2>
			<div class="description">强大的师资是教学成果的保证</div>
			<div class="teacher-list">
				<ul class="cf"><?php foreach($teachers as $t){
					echo Html::link(Html::img($t['thumbnail'], File::PIC_RESIZE, array(
						'dw'=>180,
						'dh'=>228,
						'alt'=>Html::encode($t['title']),
						'after'=>array(
							'tag'=>'span',
							'text'=>Html::encode($t['title']),
						),
					)), array('teacher'), array(
						'encode'=>false,
						'title'=>Html::encode($t['title']),
						'wrapper'=>'li',
					));
				}?></ul>
			</div>
		</div>
		<div class="more-description">
			<?php echo String::nl2p(Html::encode($cat_teacher['description']))?>
		</div>
	</div>
</section>
<section class="sec-1 mb70">
	<div class="container">
		<div class="row cols col2 cf">
			<h2 class="sub-title">INFORMATION</h2>
			<div class="box left news">
				<h3 class="box-title">
					<span>画室资讯</span>
				</h3>
				<div class="box-content">
					<ul><?php foreach($news as $n){
						echo Html::link($n['title'], array('news-'.$n['id']), array(
							'before'=>array(
								'tag'=>'time',
								'text'=>date('Y-m-d', $n['publish_time']),
							),
							'wrapper'=>'li',
						));
					}?></ul>
					<?php echo Html::link('+MORE', array('news'), array(
						'class'=>'more-link',
					))?>
				</div>
			</div>
			<div class="box right works">
				<h3 class="box-title">
					<span>师生作品</span>
				</h3>
				<div class="box-content">
					<ul class="cf">
					<?php foreach($works as $w){
						echo Html::link(Html::img($w['thumbnail'], File::PIC_RESIZE, array(
							'dw'=>198,
							'dh'=>156,
							'alt'=>Html::encode($w['title']),
						)), array('works-'.$w['id']), array(
							'encode'=>false,
							'title'=>Html::encode($w['title']),
							'append'=>array(
								'tag'=>'span',
								'class'=>'zoom-bg',
								'text'=>'',
								'after'=>array(
									'tag'=>'span',
									'class'=>'zoom-icon',
									'text'=>'',
								),
							),
							'wrapper'=>'li',
						));
					}?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
$(function(){
	$('#environment').on('click', 'li a', function(){
		$(this).next('p').slideToggle();
		$(this).parent().toggleClass('act').siblings().removeClass('act').find('p').slideUp();
	});
});
</script>