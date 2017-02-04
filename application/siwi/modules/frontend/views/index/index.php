<?php
$this->appendCss($this->appAssets('css/index.css'));
?>
<div class="clearfix col2">
	<section class="clearfix collect-list">
		<?php $listview->showData()?>
	</section>
	<?php $listview->showPager()?>
</div>
<script src="<?php echo $this->appAssets('js/index.js')?>"></script>
<script>
index.init();
</script>