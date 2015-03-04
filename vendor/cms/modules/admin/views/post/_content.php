<?php
use fay\models\tables\Posts;
use fay\helpers\Html;

if(isset($post['content_type'])){
	$editor = $post['content_type'];
}else{
	$editor = F::form('setting')->getData('editor');
}
if($editor == Posts::CONTENT_TYPE_TEXTAREA){
	echo F::form()->textarea('content', array(
		'class'=>'h350 wp99 autosize',
	));
}else if($editor == Posts::CONTENT_TYPE_MARKDOWN){
	echo F::form()->textarea('content', array(
		'id'=>'wmd-input',
		'class'=>'h350',
		'style'=>'width:49%;',
		'wrapper'=>array(
			'tag'=>'div',
			'id'=>'markdown-container',
		),
		'after'=>'<div class="clear"></div>',
	));
}else{
	echo F::form()->textarea('content', array(
		'id'=>'visual-editor',
		'class'=>'h350',
	));
}
echo Html::inputHidden('content_type', $editor);