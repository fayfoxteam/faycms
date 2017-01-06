<?php
use fay\services\OptionService;
use fay\helpers\Html;
?>
<div class="page-header-fixed">
	<nav class="navigator">
		<div class="nav-left">
			<?php F::widget()->load('nav-left');?>
		</div>
		<div class="nav-center">
			<div class="logo"><?php
				$logo = OptionService::get('site:logo');
				if($logo){
					echo Html::img($logo);
				}else{
					echo Html::img($this->appStatic('images/logo.png'));
				}
			?></div>
		</div>
		<div class="nav-right">
			<?php F::widget()->load('nav-right');?>
		</div>
	</nav>
</div>