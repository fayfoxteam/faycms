<?php
use fay\helpers\Html;
use fay\models\File;
use fay\helpers\Date;

$this->appendCss($this->appStatic('css/blog.css'));
?>
<div class="g-sub-hd">
	<div class="post-info">
		<div class="avatar">
			<?php echo Html::link(Html::img($post['avatar'], File::PIC_THUMBNAIL, array(
				'alt'=>$post['nickname']
			)), array('u/'.$post['user_id']), array(
				'encode'=>false,
				'title'=>false,
			))?>
		</div>
		<div class="info">
			<h1><?php echo Html::encode($post['title'])?></h1>
			<span class="meta">
				作者：<?php echo Html::link($post['nickname'], array('u/'.$post['user_id']))?>
				|
				<?php echo Date::niceShort($post['publish_time'])?>
				<br />
				<?php echo Html::link($post['cat_title'], array('c/'.$post['cat_id']))?>
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
					echo Html::link('', array('file/download', array(
						'id'=>$post['files'][0]['file_id'],
					)), array(
						'class'=>'icon-download',
						'title'=>'下载附件',
						'target'=>'_blank',
					));
				}
				echo Html::link('', 'javascript:;', array(
					'class'=>'check-login icon-heart like-link'.($liked ? ' liked' : ''),
					'title'=>'赞',
					'data-id'=>$post['id'],
				));
				echo Html::link('', 'javascript:;', array(
					'class'=>'check-login icon-star favourite-link'.($favored ? ' favored' : ''),
					'title'=>'收藏',
					'data-id'=>$post['id'],
				));
				echo Html::link('', 'http://www.jiathis.com/share', array(
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
				<?php echo Html::inputHidden('target', $post['id'])?>
				<?php echo Html::inputHidden('parent', 0)?>
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
		<?php \F::app()->widget->render('recent_posts')?>
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