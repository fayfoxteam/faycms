<?php
use fay\helpers\Html;
?>
<div class="cat-list">
	<h3>分类目录</h3>
	<ul>
	<?php foreach($cats as $c){
		echo Html::link($c['title'], array('cat/'.$c['id']), array(
			'wrapper'=>'li',
			//'class'=>$c['id'] == $post['cat_id'] ? 'crt' : false,
		));
	}?>
	</ul>
</div>