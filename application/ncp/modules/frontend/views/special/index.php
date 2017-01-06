<?php
use fay\helpers\HtmlHelper;
use ncp\helpers\FriendlyLink;
use fay\services\FileService;
$this->appendCss($this->appStatic('css/special.css'));
?>
<style type="text/css">
body{background:#e4e4e4}
</style>
<div class="container containerbg">
	<div class="curnav">
		<strong>当前位置：</strong><a href="/">首页</a>&gt;<span>农专题</span>
	</div>
	<div class="sp_info">
		<div class="sp_left">
			<ul><?php $listview->showData()?></ul>
		</div>
		<div class="news_right">
			<div class="d-fr">
				<h3>农产品推荐</h3>
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
	<?php $listview->showPager(array(
		'type'=>'news_list',
		'params'=>array()
	))?>
</div>
