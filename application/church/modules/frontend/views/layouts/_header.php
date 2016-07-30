<?php
use fay\services\Option;
use fay\helpers\Html;
?>
<header class="page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-5 logo-container">
				<a href="<?php echo $this->url()?>"><?php
					$logo = Option::get('site:logo');
					if($logo){
						echo Html::img($logo);
					}else{
						echo Html::img($this->appStatic('images/logo.png'));
					}
					?></a>
			</div>
			<nav class="col-md-7 main-menu">
				<?php F::widget()->load('main-menu')?>
			</nav>
		</div>
	</div>
</header>