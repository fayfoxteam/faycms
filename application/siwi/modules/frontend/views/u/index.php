<?php
use fay\helpers\HtmlHelper;
use fay\services\FileService;
use fay\helpers\StringHelper;
use fay\helpers\DateHelper;
?>
<div class="clearfix col2">
	<div class="g-mnc">
		<section class="clearfix work-list">
			<h2>作品</h2>
			<?php foreach($works as $k => $p){?>
			<article class="<?php if(++$k % 3 == 0)echo 'last'?>">
				<?php echo HtmlHelper::link(HtmlHelper::img($p['thumbnail'], FileService::PIC_RESIZE, array(
					'dw'=>283,
					'dh'=>217,
					'alt'=>HtmlHelper::encode($p['title']),
					'title'=>HtmlHelper::encode($p['title']),
				)), array('material/'.$p['id']) ,array(
					'encode'=>false,
					'title'=>HtmlHelper::encode($p['title']),
				));?>
				<div class="meta">
					<h3><?php echo HtmlHelper::link($p['title'], array('material/'.$p['id']), array(
						'title'=>HtmlHelper::encode($p['title']),
						'encode'=>false,
					))?></h3>
					<p class="cat">
						<?php echo HtmlHelper::link($p['parent_cat_title'], array('material/cat-'.$p['parent_cat_id']))?>
						-
						<?php echo HtmlHelper::link($p['cat_title'], array('material/cat-'.$p['cat_id']))?>
					</p>
				</div>
			</article>
			<?php }?>
		</section>
		<section class="clearfix post-list">
			<h2>博客</h2>
			<?php foreach($posts as $p){?>
			<article class="clearfix">
				<div class="thumbnail"><?php 
					if($p['thumbnail']){
						echo HtmlHelper::link(HtmlHelper::img($p['thumbnail'], FileService::PIC_RESIZE, array(
							'dw'=>300,
							'dh'=>230,
							'alt'=>HtmlHelper::encode($p['title']),
							'title'=>HtmlHelper::encode($p['title']),
						)), array('blog/'.$p['id']) ,array(
							'encode'=>false,
							'title'=>HtmlHelper::encode($p['title']),
						));
					}else{
						echo HtmlHelper::link("<img src='{$this->url()}images/no-image.jpg' width='300' height='230' />", array('blog/'.$p['id']) ,array(
							'encode'=>false,
							'title'=>HtmlHelper::encode($p['title']),
						));
					}
				?></div>
				<div class="con">
					<header>
						<h3><?php echo HtmlHelper::link(StringHelper::niceShort($p['title'], 38, true), array('blog/'.$p['id']), array(
							'title'=>HtmlHelper::encode($p['title']),
							'encode'=>false,
						))?></h3>
						<div class="meta">
							<span class="author">
								作者：
								<?php echo HtmlHelper::link($p['nickname'], array('u/'.$p['user_id']))?>
							</span>
							|
							<span class="date"><?php echo DateHelper::niceShort($p['publish_time'])?></span>
							|
							<span class="comment"><?php echo $p['comments']?> 评论</span>
						</div>
					</header>
					<div class="abstract"><?php echo HtmlHelper::encode($p['abstract'])?></div>
					<?php echo HtmlHelper::link('阅读全文', array('blog/'.$p['id']), array(
						'class'=>'fr btn-blue more-link',
					))?>
				</div>
			</article>
			<?php }?>
		</section>
		<section class="clearfix message-container">
			<h2>留言</h2>
			<ul class="message-list">
			<?php $listview->showData();?>
			</ul>
			<?php $listview->showPager();?>
		</section>
		<section class="clearfix create-message-container">
			<h2>我要留言</h2>
			<form id="create-message-form">
				<?php echo HtmlHelper::inputHidden('target', $this->user_id)?>
				<?php echo HtmlHelper::inputHidden('parent', 0)?>
				<textarea name="content"></textarea>
				<a href="javascript:;" class="btn-red check-login fr" id="create-message-form-submit">发表评论</a>
			</form>
		</section>
	</div>
	<div class="g-sd">
		<?php \F::widget()->render('siwi/recent_posts')?>
	</div>
</div>
<script src="<?php echo $this->assets('static/siwi/js/home.js')?>"></script>
<script>
home.user_id = <?php echo $this->user_id?>;
home.init();
</script>