<?php
namespace pharmrich\modules\frontend\views\sidebar;

use fay\helpers\Html;

if(!function_exists('pharmrich\modules\frontend\views\sidebar\renderCats')){
	function renderCats($cats, $dep = 0){
		$html = '<ul';
		$html .= $dep ? ' class="children"' : '';
		$html .= '>';
		foreach($cats as $c){
			$html .= '<li class="cat-item">';
			$html .= Html::link($c['title'], $c['link']);
			if(!empty($c['children'])){
				$html .= renderCats($c['children'], ++$dep);
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
	<?php echo renderCats($cats)?>
</div>