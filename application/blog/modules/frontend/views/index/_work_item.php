<?php
use fay\models\Post;
use fay\helpers\Date;
use fay\helpers\Html;
use fay\models\File;

preg_match_all('/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/', $data['content'], $matches);
$post_cats = Post::model()->getCats($data['id']);
$work_files = array();
$i = 0;
foreach($matches[1] as $m){
	if($i++ == 8){
		break;
	}
	preg_match('/\/f\/(.*?)\//', $m, $f);
	$work_files[] = $f[1];
}
?>
<article class="post-list-item">
	<div class="post-title">
		<h1>
			<a href="<?php echo $this->url('work/'.$data['id'])?>"><?php echo $data['title']?></a>
		</h1>
		<span class="post-meta">
			发表于 
			<time><?php echo Date::format($data['publish_time'])?></time>
		</span>
		<div class="clear"></div>
	</div>
	<div class="post-content">
		<div class="post-abstract"><?php echo $data['abstract']?></div>
	<?php foreach($work_files as $wf){?>
		<div class="work-file-item">
			<a href="<?php echo $this->url('file/pic', array('t'=>1, 'f'=>$wf))?>">
				<?php echo Html::img($wf, File::PIC_ZOOM, array(
					'dw'=>147,
					'dh'=>147,
				))?>
			</a>
		</div>
	<?php }?>
		<div class="clear"></div>
	</div>
	<div class="post-tags">
		<?php
		echo Html::link('<span>#'.Html::encode($data['cat_title']).'</span>', array('cat/'.$data['cat_id']), array(
			'class'=>'post-type',
			'title'=>Html::encode($data['cat_title']),
			'encode'=>false,
		));
		foreach($post_cats as $pc){
			echo Html::link('<span>#'.Html::encode($pc['title']).'</span>', array('cat/'.$pc['id']), array(
				'class'=>'post-type',
				'title'=>Html::encode($pc['title']),
				'encode'=>false,
			));
		}
		echo Html::link('查看详细', array('work/'.$data['id']), array(
			'class'=>'post-more-link',
		));
		?>
		<div class="clear"></div>
	</div>
</article>