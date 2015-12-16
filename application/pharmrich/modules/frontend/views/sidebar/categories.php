<?php
namespace pharmrich\modules\frontend\views\sidebar;

use fay\helpers\Html;

if(!function_exists('pharmrich\modules\frontend\views\sidebar\renderCats')){
	function renderCats($cats, $uri, $dep = 0){
		$html = '<ul';
		$html .= $dep ? ' class="children"' : '';
		$html .= '>';
		foreach($cats as $c){
			$html .= '<li class="cat-item">';
			$html .= Html::link($c['title'], array(str_replace(array(
				'{$id}', '{$alias}',
			), array(
				$c['id'], $c['alias'],
			), $uri)));
			if(!empty($c['children'])){
				$html .= renderCats($c['children'], $uri, ++$dep);
			}
			$html .= '</li>';
		}
		$html .= '</ul>';
		return $html;
	}
}
?>
<div class="widget categories">
	<h3><?php echo $config['title']?></h3>
	<?php echo renderCats($cats, $config['uri'])?>
</div>