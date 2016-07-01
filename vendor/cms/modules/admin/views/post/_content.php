<?php
use fay\models\tables\Posts;
use fay\helpers\Html;

$editor = F::form()->getData('content_type');
$editor || $editor = F::form('setting')->getData('editor', Posts::CONTENT_TYPE_VISUAL_EDITOR);

if($editor == Posts::CONTENT_TYPE_TEXTAREA){
	echo F::form()->textarea('content', array(
		'class'=>'h350 form-control autosize',
	));
}else if($editor == Posts::CONTENT_TYPE_MARKDOWN){
	echo Html::textarea('content', F::form()->getData('markdown', '', false), array(
		'id'=>'wmd-input',
		'class'=>'h350 wp100',
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