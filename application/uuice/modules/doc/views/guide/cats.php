<?php
use fay\models\Post;
use fay\helpers\Html;

foreach($posts as $p){
	$this->renderPartial('_panel', array(
		'id'=>$p['id'],
		'title'=>$p['title'],
		'body'=>Post::formatContent($p),
		'file_link'=>Post::model()->getPropValueByAlias('file_link', $p['id']),
	));
}
?>
<div class="panel">
	<div class="panel-header">
		<h2>目录</h2>
	</div>
	<div class="panel-body">
		<ul><?php foreach($cats as $c){
			echo Html::link($c['title'].($c['description'] ? "（{$c['description']}）" : ''), array($c['alias']), array(
				'wrapper'=>'li',
			));
		}?></ul>
	</div>
</div>