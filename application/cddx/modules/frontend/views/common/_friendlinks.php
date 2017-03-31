<?php
use fay\services\CategoryService;
use fay\services\LinkService;
use fay\helpers\HtmlHelper;
use fay\services\file\FileService;

$links_has_logo = LinkService::service()->getLinksHasLogo(null, 10);
$link_cats = CategoryService::service()->getChildren('_system_link');
?>
<div class="w1000">
	<div class="box" id="footer-links">
		<div class="box-title">
			<h3><span>相关单位链接</span><em></em></h3>
		</div>
		<div class="box-content">
			<p><?php foreach($links_has_logo as $l){
				echo HtmlHelper::link(HtmlHelper::img($l['logo'], FileService::PIC_ORIGINAL, array(
					'alt'=>HtmlHelper::encode($l['title']),
				)), $l['url'], array(
					'target'=>$l['target'],
					'encode'=>false,
					'title'=>HtmlHelper::encode($l['title']),
				));
			}?></p>
			<p><?php
				foreach($link_cats as $c){
					$links = LinkService::service()->getByCat($c);
					echo HtmlHelper::select('', array(''=>'------'.$c['title'].'------')+HtmlHelper::getSelectOptions($links, 'url', 'title'));
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