<?php

function listCats($cat_map, $dep = 0){
	$pre = str_repeat(' - ', $dep);
	$out = '';
	foreach($cat_map as $key => $val){
		echo F::form()->inputCheckbox('post_category[]', $val['id'], array(
		'disabled'=>F::form()->getData('cat_id') == $val['id'] ? 'disabled' : false,
		'checked'=>F::form()->getData('cat_id') == $val['id'] ? 'checked' : false,
		'wrapper'=>array(
		'tag'=>'label',
		'append'=>$pre.$val['title'],
		'wrapper'=>'p'
		)
		));
		if(isset($val['children']) && is_array($val['children'])){
		listCats($val['children'], $dep + 1);
		}
		}
		return $out;
		}
		?>
		<div class="box" id="box-category" data-name="category">
			<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>附加分类</h4>
	</div>
	<div class="box-content">
		<?php echo listCats($cats);?>
	</div>
</div>