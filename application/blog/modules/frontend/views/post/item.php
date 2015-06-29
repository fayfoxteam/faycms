<?php
use fay\helpers\Date;
use fay\helpers\Html;
use fay\models\tables\Messages;
use fay\models\Post;
?>
<div class="col-main">
	<article class="post-item">
		<header>
			<div class="post-meta">
				发表于<time><?php echo Date::format($post['publish_time'])?></time>
			</div>
			<h1><?php echo Html::encode($post['title'])?></h1>
		</header>
		<div class="post-content">
			<?php echo Post::formatContent($post)?>
		</div>
		<div class="post-tags">
			<?php echo Html::link('<span>#'.Html::encode($post['cat_title']).'</span>', array('cat/'.$post['cat_id']), array(
				'class'=>'post-type',
				'encode'=>false,
				'title'=>Html::encode($post['cat_title']),
			));?>
			<?php foreach($post['ext_cats'] as $pc){
				echo Html::link('<span>#'.Html::encode($pc['title']).'</span>', array('cat/'.$pc['id']), array(
					'class'=>'post-type',
					'encode'=>false,
					'title'=>Html::encode($pc['title']),
				));
			}?>
			<div class="clear"></div>
		</div>
	</article>
	<nav class="nav-single">
		<?php if(!empty($post['nav']['prev'])){?>
		<span class="nav-previous">
			<a rel="prev" href="<?php echo $this->url('post/'.$post['nav']['prev']['id'])?>">
				<span class="meta-nav">←</span>
				<?php echo Html::encode($post['nav']['prev']['title'])?>
			</a>
		</span>
		<?php }?>
		<?php if(!empty($post['nav']['next'])){?>
		<span class="nav-next">
			<a rel="next" href="<?php echo $this->url('post/'.$post['nav']['next']['id'])?>">
				<?php echo Html::encode($post['nav']['next']['title'])?>
				<span class="meta-nav">→</span>
			</a>
		</span>
		<?php }?>
	</nav>
	<div class="leave-message">
		<form id="leave-message-form">
			<input type="hidden" name="target" value="<?php echo $post['id']?>" />
			<input type="hidden" name="type" value="<?php echo Messages::TYPE_POST_COMMENT?>" />
			<textarea class="leave-message-input" name="content"></textarea>
		</form>
		<div class="leave-message-submit-container">
			<a href="javascript:;" class="btn-1" id="leave-message-submit">发布</a>
		</div>
		<div class="message-list" id="post-message-list">
			<ul>
			<?php foreach($post['messages'] as $m){?>
				<li>
					<div class="message-time"><?php echo Date::format($m['create_time'])?></div>
					<div class="message-content"><?php echo Html::encode($m['content'])?></div>
					<div class="clear"></div>
				</li>
			<?php }?>
			</ul>
		</div>
	</div>
</div>
<div class="col-side">
	<?php echo F::widget()->render('profile')?>
	<?php echo F::widget()->render('contact')?>
	<?php echo F::widget()->render('fay/category_post', array(
		'title'=>'相关文章',
		'top'=>$post['cat_id'],
		'subclassification'=>true,
		'order'=>'rand',
	))?>
	<div class="fixed-content">
		<?php echo F::widget()->load('categories')?>
	</div>
</div>
<div class="clear"></div>

<script type="text/javascript" src="<?php echo $this->assets('js/jquery.sourcerer.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('js/prettify.js')?>"></script>
<script>
$(function(){
	$('.showCode').sourcerer('js html css php'); // Display all languages
	$('.showCodeJS, .lang-js').sourcerer('js'); // Display JS only
	$('.showCodeHTML').sourcerer('html'); // Display HTML only
	$('.showCodePHP, .lang-php').sourcerer('php'); // Display PHP only
	$('.showCodeCSS').sourcerer('css'); // Display CSS only

	prettyPrint();

	$("#leave-message-submit").click(function(){
		$.ajax({
			type: "POST",
			url: system.url("message/create"),
			data: $("#leave-message-form").serialize(),
			dataType: 'json',
			cache: false,
			success: function(data){
				$(".leave-message-input").val("");
				var html = '<li>';
				html += '	<div class="message-time">'+system.date(data.message.create_time)+'</div>';
				html += '	<div class="message-content">'+system.encode(data.message.content)+'</div>';
				html += '	<div class="clear"></div>';
				html += '</li>';
				$("#post-message-list ul").prepend(html);
			}
		});
	});
});
</script>