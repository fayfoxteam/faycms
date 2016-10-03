<?php

function listCats($cat_map, $dep = 0){
	$pre = str_repeat(' - ', $dep);
	$out = '';
	foreach($cat_map as $key => $val){
		$out .= '<p>';
		$out .= F::form()->inputCheckbox('page_category[]', $val['id'], array(
			'label'=>$pre . $val['title'],
			'checked'=>F::form()->getData('cat_id') == $val['id'] ? 'checked' : false,
		));
		$out .= '</p>';
		if(isset($val['children']) && is_array($val['children'])){
			$out .= listCats($val['children'], $dep + 1);
		}
	}
	return $out;
}
?>
<div class="box" id="box-category" data-name="category">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>分类</h4>
	</div>
	<div class="box-content">
		<?php echo listCats($cats);?>
	</div>
</div>