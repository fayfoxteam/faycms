<?php
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;
?>
<div class="col-main">
	<article class="post-item">
		<header>
			<div class="post-meta">
				发表于<time><?php echo DateHelper::format($post['post']['publish_time'])?></time>
			</div>
			<h1><?php echo HtmlHelper::encode($post['post']['title'])?></h1>
		</header>
		<div class="post-content">
			<?php echo $post['post']['content']?>
		</div>
		<div class="post-tags">
			<?php echo HtmlHelper::link('<span>#'.HtmlHelper::encode($post['category']['title']).'</span>', array('cat/'.$post['category']['id']), array(
				'class'=>'post-type',
				'encode'=>false,
				'title'=>HtmlHelper::encode($post['category']['title']),
			));?>
			<?php foreach($post['categories'] as $pc){
				echo HtmlHelper::link('<span>#'.HtmlHelper::encode($pc['title']).'</span>', array('cat/'.$pc['id']), array(
					'class'=>'post-type',
					'encode'=>false,
					'title'=>HtmlHelper::encode($pc['title']),
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
				<?php echo HtmlHelper::encode($post['nav']['prev']['title'])?>
			</a>
		</span>
		<?php }?>
		<?php if(!empty($post['nav']['next'])){?>
		<span class="nav-next">
			<a rel="next" href="<?php echo $this->url('post/'.$post['nav']['next']['id'])?>">
				<?php echo HtmlHelper::encode($post['nav']['next']['title'])?>
				<span class="meta-nav">→</span>
			</a>
		</span>
		<?php }?>
	</nav>
	<div class="leave-message">
		<form id="leave-message-form">
			<input type="hidden" name="target" value="<?php echo $post['id']?>" />
			<textarea class="leave-message-input" name="content"></textarea>
		</form>
		<div class="leave-message-submit-container">
			<a href="javascript:;" class="btn-1" id="leave-message-submit">发布</a>
		</div>
		<div class="message-list" id="post-message-list">
			<ul>
			<?php foreach($post['messages'] as $m){?>
				<li>
					<div class="message-time"><?php echo DateHelper::format($m['create_time'])?></div>
					<div class="message-content"><?php echo HtmlHelper::encode($m['content'])?></div>
					<div class="clear"></div>
				</li>
			<?php }?>
			</ul>
		</div>
	</div>
</div>
<div class="col-side">
	<?php F::widget()->render('profile')?>
	<?php F::widget()->render('contact')?>
	<?php F::widget()->render('fay/category_posts', array(
		'title'=>'相关文章',
		'top'=>$post['post']['cat_id'],
		'subclassification'=>true,
		'order'=>'rand',
	))?>
	<div class="fixed-content">
		<?php F::widget()->load('categories')?>
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