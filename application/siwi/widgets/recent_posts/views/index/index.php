<?php 
use fay\services\FileService;
use fay\helpers\Html;
use fay\helpers\StringHelper;
?>
<aside class="m-recent-posts">
	<h3>最新博文</h3>
	<ul>
	<?php foreach($posts as $p){?>
		<li class="clearfix">
			<?php 
			if($p['thumbnail']){
				echo Html::link(Html::img($p['thumbnail'], FileService::PIC_RESIZE, array(
					'dw'=>100,
					'dh'=>78,
					'alt'=>Html::encode($p['title']),
					'title'=>Html::encode($p['title']),
				)), array('blog/'.$p['id']), array(
					'encode'=>false,
					'title'=>Html::encode($p['title']),
					'alt'=>Html::encode($p['title']),
				));
			}else{
				echo Html::link("<img src='{$this->url()}images/no-image.jpg' width='100' height='78' />", array('blog/'.$p['id']) ,array(
					'encode'=>false,
					'title'=>Html::encode($p['title']),
					'alt'=>Html::encode($p['title']),
				));
			}
			echo Html::link(StringHelper::niceShort($p['title'], 38, true), array('blog/'.$p['id']), array(
				'title'=>Html::encode($p['title']),
				'class'=>'title',
				'encode'=>false,
			));
			?>
			<span class="meta">
				作者：
				<?php echo Html::link($p['realname'], array('u/'.$p['user_id']))?>
				|
				<?php echo $p['comments']?> 评论
			</span>
		</li>
	<?php }?>
	</ul>
</aside>