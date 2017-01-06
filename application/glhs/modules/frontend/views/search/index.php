<?php
use fay\services\OptionService;
use fay\helpers\Html;
?>
<div class="page-title">
	<div class="container">
		<h1><?php echo Html::encode($keywords)?></h1>
		<div class="breadcrumbs">
			<ol>
				<li><?php echo Html::link(OptionService::get('site:sitename'), null)?></li>
				<li><?php echo Html::encode($keywords)?></li>
			</ol>
		</div>
	</div>
</div>
<div class="container">
	<div class="sidebar">
	</div>
	<div class="main-content">
		<div class="post-list"><?php $listview->showData()?></div>
		<?php $listview->showPager()?>
	</div>
</div>