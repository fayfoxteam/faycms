<?php
use fay\helpers\HtmlHelper;
use fay\services\FileService;
use fay\helpers\DateHelper;

$this->appendCss($this->appAssets('css/blog.css'));
?>
<div class="g-sub-hd">
	<div class="post-info">
		<div class="avatar">
			<?php echo HtmlHelper::link(HtmlHelper::img($post['avatar'], FileService::PIC_THUMBNAIL, array(
				'alt'=>$post['nickname']
			)), array('u/'.$post['user_id']), array(
				'encode'=>false,
				'title'=>false,
			))?>
		</div>
		<div class="info">
			<h1><?php echo HtmlHelper::encode($post['title'])?></h1>
			<span class="meta">
				作者：<?php echo HtmlHelper::link($post['nickname'], array('u/'.$post['user_id']))?>
				|
				<?php echo DateHelper::niceShort($post['publish_time'])?>
				<br />
				<?php echo HtmlHelper::link($post['cat_title'], array('c/'.$post['cat_id']))?>
			</span>
		</div>
		<div class="clear"></div>
	</div>
</div>
<div class="clearfix col2">
	<div class="g-mnc">
		<article class="post-item">
			<?php echo $post['content']?>
			<div class="share-container">
				<?php
				if(isset($post['files'][0])){
					echo HtmlHelper::link('', array('file/download', array(
						'id'=>$post['files'][0]['file_id'],
					)), array(
						'class'=>'icon-download',
						'title'=>'下载附件',
						'target'=>'_blank',
					));
				}
				echo HtmlHelper::link('', 'javascript:;', array(
					'class'=>'check-login icon-heart like-link'.($liked ? ' liked' : ''),
					'title'=>'赞',
					'data-id'=>$post['id'],
				));
				echo HtmlHelper::link('', 'javascript:;', array(
					'class'=>'check-login icon-star favourite-link'.($favored ? ' favored' : ''),
					'title'=>'收藏',
					'data-id'=>$post['id'],
				));
				echo HtmlHelper::link('', 'http://www.jiathis.com/share', array(
					'class'=>'jiathis jiathis_separator jtico_jiathis icon-share',
					'target'=>'_blank',
					'title'=>false,
				));
				?>
			</div>
		</article>
		<div class="clearfix create-comment">
			<h2>发表评论</h2>
			<form id="create-comment-form">
				<?php echo HtmlHelper::inputHidden('target', $post['id'])?>
				<?php echo HtmlHelper::inputHidden('parent', 0)?>
				<textarea name="content"></textarea>
				<a href="javascript:;" class="btn-red check-login fr" id="create-comment-form-submit">发表评论</a>
			</form>
		</div>
		<div class="comment-container">
			<h2>全部评论（<?php echo $post['comments']?>）</h2>
			<ul>
				<?php $listview->showData();?>
			</ul>
			<?php $listview->showPager();?>
		</div>
	</div>
	<div class="g-sd">
		<?php \F::widget()->render('recent_posts')?>
	</div>
</div>
<script src="<?php echo $this->assets('static/siwi/js/blog-item.js')?>"></script>
<script>
blog_item.id = <?php echo $post['id']?>;
var jiathis_config = {
	summary:"",
	shortUrl:false,
	hideMore:false
}
$(function(){
	blog_item.init();
});
</script>