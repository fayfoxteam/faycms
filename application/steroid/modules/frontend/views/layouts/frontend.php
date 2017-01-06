<?php
use fay\services\OptionService;
use fay\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<?php if(!empty($canonical)){?>
		<link rel="canonical" href="<?php echo $canonical?>" />
	<?php }?>
	<title><?php if(!empty($title)){
			echo $title, ' | ';
		}
		echo OptionService::get('site:sitename')?></title>
	<meta content="<?php if(isset($keywords))echo Html::encode($keywords);?>" name="keywords" />
	<meta content="<?php if(isset($description))echo Html::encode($description);?>" name="description" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/animate/animate.min.css')?>" >
	<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/font-awesome.min.css')?>" >
	<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" >
	<link type="text/css" rel="stylesheet" href="<?php echo $this->appStatic('css/style.css')?>" >
	<?php echo $this->getCss()?>
	<script type="text/javascript" src="<?php echo $this->assets('js/jquery-2.2.4.min.js')?>"></script>
	<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
	<script>
		system.base_url = '<?php echo $this->url()?>';
	</script>
</head>
<body>
<div class="wrapper">
	<?php $this->renderPartial('layouts/_header')?>
	<?php $this->renderPartial('layouts/_fixed_header')?>
	<?php $this->renderPartial('layouts/_mobile_nav')?>
	<?php echo $content?>
	<div class="page-copyright">
		<div class="container">
		<div class="row">
			<div class="col-md-12">
					<p>
						<?php echo OptionService::get('site:sitename')?>
						-
						<?php
							$base_url = parse_url(F::config()->get('base_url'));
							echo $base_url['host'];
						?>
					</p>
					<p><?php echo OptionService::get('site:copyright')?></p>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->appStatic('js/common.js')?>"></script>
<script>
	$(function(){
		common.init();
	});
</script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/analyst.min.js')?>"></script>
<script>_fa.init();</script>
</body>
</html>