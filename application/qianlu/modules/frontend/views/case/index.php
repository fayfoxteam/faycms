<?php
use fay\helpers\HtmlHelper;
?>
<div id="masonry" class="masonry-fluid">
<?php foreach($cases as $c){?>
	<div class="case-item">
		<a href="<?php echo $this->url('case/'.$c['id'])?>"><?php echo HtmlHelper::img($c['thumbnail'], 4, array(
			'dw'=>203,
		))?></a>
	</div>
<?php }?>
</div>
<script>
$(function(){
	var $container = $('#masonry');
	$container.imagesLoaded(function(){
		$("#sidebar").height($("#content").height() - 12);
	});
});
</script>