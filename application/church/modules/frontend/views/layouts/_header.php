<?php
use fay\services\OptionService;
use fay\helpers\HtmlHelper;
?>
<header class="page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-5 logo-container">
				<a href="<?php echo $this->url()?>"><?php
					$logo = OptionService::get('site:logo');
					if($logo){
						echo HtmlHelper::img($logo);
					}else{
						echo HtmlHelper::img($this->appStatic('images/logo.png'));
					}
					?></a>
			</div>
			<nav class="col-md-7 main-menu">
				<?php F::widget()->load('main-menu')?>
			</nav>
		</div>
	</div>
</header>