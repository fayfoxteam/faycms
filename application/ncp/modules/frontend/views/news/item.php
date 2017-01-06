<?php
use fay\helpers\HtmlHelper;
use ncp\helpers\FriendlyLink;
use fay\services\FileService;

$this->appendCss($this->appStatic('css/news.css'));
?>
<div class="container containerbg">
	<div class="curnav">
		<strong>当前位置：</strong><a href="/">首页</a>&gt;<a href="/product/">农资讯</a>&gt;<span>五莲星冷鲜缙云麻鸭</span>
	</div>
	<div class="in_adv">
		<?php echo F::widget()->load('news-item-ad')?>
	</div>
	<div class="detail-info">
		<div class="d-fl">
			<h1><?php echo HtmlHelper::encode($post['title'])?></h1>
			<div class="detail-p">
				<?php echo $post['content']?>
			</div>
			<div class="p-next">
				<span class="fl">
					<strong>上一篇：</strong>
					<?php if(empty($post['nav']['prev'])){
						echo '没有了';
					}else{
						echo HtmlHelper::link($post['nav']['prev']['title'], FriendlyLink::getNewsLink(array(
							'id'=>$post['nav']['prev']['id'],
						)));
					}?>
				</span>
				<span class="fr">
					<strong>下一篇：</strong>
					<?php if(empty($post['nav']['next'])){
						echo '没有了';
					}else{
						echo HtmlHelper::link($post['nav']['next']['title'], FriendlyLink::getNewsLink(array(
							'id'=>$post['nav']['next']['id'],
						)));
					}?>
				</span>
			</div>
		</div>
		<div class="d-fr">
			<h3>农美食推荐</h3>
			<ul>
			<?php foreach($right_posts as $p){?>
				<li>
					<p class="p-img">
						<?php echo HtmlHelper::link(HtmlHelper::img($p['thumbnail'], FileService::PIC_RESIZE, array(
							'dw'=>180,
							'dh'=>135,
						)), FriendlyLink::getProductLink(array(
							'id'=>$p['id'],
						)), array(
							'encode'=>false,
							'title'=>HtmlHelper::encode($p['title']),
						))?>
					</p>
					<p class="p-name">
						<?php echo HtmlHelper::link($p['title'], FriendlyLink::getProductLink(array(
							'id'=>$p['id'],
						)))?>
					</p>
				</li>
			<?php }?>
			</ul>
		</div>
	</div>
</div>