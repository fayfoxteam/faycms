<?php
use fay\services\PostService;
use doc\helpers\CodeFile;
use fay\helpers\Html;

if(count($posts) > 2){
	$panel_body = '';
	foreach($posts as $p){
		if(!$p['title'])continue;//标题为空的话列出来不好看
		$panel_body .= Html::link($p['title'], '#part-'.$p['id'], array(
			'wrapper'=>'li',
		));
	}
	
	$this->renderPartial('_panel', array(
		'title'=>'目录',
		'body'=>'<ul>'.$panel_body.'</ul>',
	));
}
foreach($posts as $p){
	$file_link = PostService::service()->getPropValueByAlias('file_link', $p['id']);
	if($file_link){
		$function = PostService::service()->getPropValueByAlias('file_function', $p['id']);
		if($function){
			$line = CodeFileService::getLineByFunctionName($function, BASEPATH.'../'.$file_link);
			if($line){
				$file_link .= '#LC'.$line;
			}
		}
	}
	$this->renderPartial('_panel', array(
		'id'=>$p['id'],
		'title'=>$p['title'],
		'body'=>Post::formatContent($p),
		'file_link'=>$file_link,
	));
}