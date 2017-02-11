<?php
/**
 * @var $data array
 * @var $end_time int
 */
?>
<article>
	<div class="img"><a href=""><?php
		if($data['photo']){
			//已经下载到本地，从本地输出
			echo \fay\helpers\HtmlHelper::img($data['photo'], \fay\services\FileService::PIC_RESIZE, array(
				'dw'=>400,
			));
		}else{
			//还在微信服务器，通过媒体ID输出
			echo \fay\helpers\HtmlHelper::img('');
		}
	?></a></div>
	<div class="meta">
		<a href=""><?php echo $data['id'], '.', \fay\helpers\HtmlHelper::encode($data['name'])?></a>
	</div>
	<div class="blessing">
		<?php echo \fay\helpers\HtmlHelper::encode($data['blessing'])?>
	</div>
	<div class="vote-container">
		<?php echo \fay\helpers\HtmlHelper::link('投票', 'javascript:;', array(
			'class'=>'btn wp100 vote-link ' . ($end_time < \F::app()->current_time ? 'btn-grey' : 'btn-blue'),
			'data-id'=>$data['id'],
			'prepend'=>'<i class="fa fa-thumbs-up"></i>',
		))?>
	</div>
	<div class="vote-result"><?php echo $data['votes']?>票</div>
</article>
