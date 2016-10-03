<?php 
use fay\helpers\Html;

echo Html::textarea('content', isset($data['content']) ? $data['content'] : '', array(
	'id'=>'visual-editor',
	'class'=>'h200 visual-simple',
));