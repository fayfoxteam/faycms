<?php
use fay\helpers\Html;
use fay\models\Post;

foreach($posts as $p){
	$this->renderPartial('guide/_panel', array(
		'id'=>$p['id'],
		'title'=>$p['title'],
		'body'=>Post::formatContent($p),
		'file_link'=>Post::model()->getPropValueByAlias('file_link', $p['id']),
	));
}
?>
<div class="panel">
	<div class="panel-header">
		<h2>最近更新</h2>
	</div>
	<div class="panel-body">
		<ul><?php foreach($last_modified_cats as $c){
			echo Html::link($c['title'].($c['description'] ? "（{$c['description']}）" : ''), array($c['alias'] == 'fayfox' ? null : $c['alias']), array(
				'wrapper'=>'li',
			));
		}?></ul></div>
</div>