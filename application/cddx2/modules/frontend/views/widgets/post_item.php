<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;
?>
<div class="inner cf">
	<div class="breadcrumbs">
		<?php
		echo HtmlHelper::link('网站首页', array('')),
			' &gt; ',
			HtmlHelper::link($post['post']['cat_title'], array('cat/'.$post['post']['cat_id'])),
			' &gt; ',
			HtmlHelper::encode($post['post']['title']);
		?>
	</div>
	<div class="g-sd">
		<?php F::widget()->load('left-cats')?>
	</div>
	<div class="g-mn">
		<h1 class="post-title"><?php echo HtmlHelper::encode($post['post']['title'])?></h1>
		<div class="post-meta">
			<span>撰写人：<?php echo $post['user']['user']['nickname'] ? $post['user']['user']['nickname'] : $post['user']['user']['username']?></span>
			<span>发布时间：<?php echo DateHelper::niceShort($post['post']['publish_time'])?></span>
			<span>阅读数：<?php echo $post['post']['views']?></span>
		</div>
		<div class="post-content"><?php echo $post['post']['content']?></div>
		<?php if($post['files']){?>
		<div class="attachment">
			<h3>附件：</h3>
			<ul>
			<?php foreach($post['files'] as $f){
				echo HtmlHelper::link($f['description'], $this->url('file/download', array('id'=>$f['file_id'])), array(
					'target'=>'_blank',
				));
			}?>
			</ul>
		</div>
		<?php }?>
	</div>
</div>