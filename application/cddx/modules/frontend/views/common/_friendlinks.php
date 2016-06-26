<?php
use fay\models\Category;
use fay\models\Link;
use fay\helpers\Html;
use fay\services\File;

$links_has_logo = Link::model()->getLinksHasLogo(null, 10);
$link_cats = Category::model()->getChildren('_system_link');
?>
<div class="w1000">
	<div class="box" id="footer-links">
		<div class="box-title">
			<h3><span>相关单位链接</span><em></em></h3>
		</div>
		<div class="box-content">
			<p><?php foreach($links_has_logo as $l){
				echo Html::link(Html::img($l['logo'], File::PIC_ORIGINAL, array(
					'alt'=>Html::encode($l['title']),
				)), $l['url'], array(
					'target'=>$l['target'],
					'encode'=>false,
					'title'=>Html::encode($l['title']),
				));
			}?></p>
			<p><?php
				foreach($link_cats as $c){
					$links = Link::model()->getByCat($c);
					echo Html::select('', array(''=>'------'.$c['title'].'------')+Html::getSelectOptions($links, 'url', 'title'));
				}
			?></p>
		</div>
	</div>
</div>
<script>
//友情链接
$('#footer-links').on('change', 'select', function(){
	window.open($(this).val());
});
</script>