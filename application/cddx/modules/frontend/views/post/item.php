<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;
use fay\services\PostService;
?>
<div class="inner cf">
	<div class="breadcrumbs">
		<?php
		echo HtmlHelper::link('网站首页', array('')),
			' &gt; ',
			HtmlHelper::link($post['post']['cat_title'], array('cat-'.$post['post']['cat_id'])),
			' &gt; ',
			HtmlHelper::encode($post['post']['title']);
		?>
	</div>
	<div class="g-sd">
		<div class="cat-list">
			<?php if($left_cats['alias'] != '__root__'){?>
				<h3><?php echo HtmlHelper::encode($left_cats['title'])?></h3>
			<?php }?>
			<ul>
			<?php foreach($left_cats['children'] as $c){
				echo HtmlHelper::link($c['title'], array('cat-'.$c['id']), array(
					'wrapper'=>'li',
					'class'=>$c['id'] == $post['post']['cat_id'] ? 'crt' : false,
				));
			}?>
			</ul>
		</div>
	</div>
	<div class="g-mn">
		<h1 class="post-title"><?php echo HtmlHelper::encode($post['post']['title'])?></h1>
		<div class="post-meta">
			<span>发布部门：<?php $departmeng = PostService::service()->getPropValueByAlias('department', $post['post']['id']);echo $departmeng['title']?></span>
			<span>撰写人：<?php echo $post['user']['nickname'] ? : $post['user']['username']?></span>
			<span>签发人：<?php echo PostService::service()->getPropValueByAlias('reviewer', $post['post']['id'])?></span>
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