<?php
use fay\services\Option;
use fay\helpers\Html;
?>
<header class="page-header">
	<nav class="navigator">
		<div class="nav-left">
			<?php F::widget()->load('nav-left');?>
		</div>
		<div class="nav-center">
			<div class="logo">
				<img src="<?php echo $this->appStatic('images/logo.png')?>">
			</div>
			<div class="title">
				<span>SteroidSolution.com</span>
			</div>
		</div>
		<div class="nav-right">
			<?php F::widget()->load('nav-right');?>
		</div>
	</nav>
</header>