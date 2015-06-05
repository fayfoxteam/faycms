<?php
use fay\helpers\Html;
use ncp\helpers\FriendlyLink;
use fay\models\File;
$this->appendCss($this->staticFile('css/news.css'));
?>
<style type="text/css">
body{background:#e4e4e4}
</style>
<div class="container containerbg">
	<div class="curnav">
		<strong>当前位置：</strong><a href="/">首页</a>&gt;<span>农专题</span>
	</div>
	<div class="news_info">
		<div class="news_left">
			<ul><?php $listview->showData()?></ul>
		</div>
		<div class="news_right">
			<div class="d-fr">
				<h3>农产品推荐</h3>
				<ul>
				<?php foreach($right_posts as $p){?>
					<li>
						<p class="p-img">
							<?php echo Html::link(Html::img($p['thumbnail'], File::PIC_RESIZE, array(
								'dw'=>180,
								'dh'=>135,
							)), FriendlyLink::getProductLink(array(
								'id'=>$p['id'],
							)), array(
								'encode'=>false,
								'title'=>Html::encode($p['title']),
							))?>
						</p>
						<p class="p-name">
							<?php echo Html::link($p['title'], FriendlyLink::getProductLink(array(
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
		'type'=>'special_list',
		'params'=>array()
	))?>
</div>
