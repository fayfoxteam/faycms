<?php
use fay\helpers\Html;
use fay\models\File;
use fay\models\tables\Files;

if($data['thumbnail']){
	$img = Html::img($data['thumbnail'], File::PIC_RESIZE, array(
		'dw'=>211,
		'dh'=>155,
	));
}else{
	//获取内容的第一张图片
	preg_match('/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/', $data['content'], $matches);
	if(isset($matches[1])){
		$filename = substr(basename($matches[1]), 0, -4);
		$file = Files::model()->fetchRow(array(
			'raw_name = ?'=>$filename,
		));
		$img = Html::img($file['id'], File::PIC_RESIZE, array(
			'dw'=>211,
			'dh'=>155,
		));
	}else{
		//默认图片
		$img = Html::img(0, File::PIC_ORIGINAL, array(
			'spare'=>'default',
		));
	}
}
?>
<div class="gallery-item">
	<?php
	echo Html::link($img, array('post/'.$data['id']), array(
		'encode'=>false,
		'title'=>Html::encode($data['title']),
	));
	echo Html::link($data['title'], array('post/'.$data['id']));
	?>
</div>