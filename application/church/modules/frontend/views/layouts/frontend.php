<?php
use fay\services\Option;
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
		echo Option::get('site:sitename')?></title>
	<meta content="<?php if(isset($keywords))echo Html::encode($keywords);?>" name="keywords" />
	<meta content="<?php if(isset($description))echo Html::encode($description);?>" name="description" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" >
	<link type="text/css" rel="stylesheet" href="<?php echo $this->appStatic('css/style.css')?>" >
	<?php echo $this->getCss()?>
	<script type="text/javascript" src="<?php echo $this->assets('js/jquery-2.2.4.min.js')?>"></script>
	<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
	<script>
		system.base_url = '<?php echo $this->url()?>';
		system.user_id = '<?php echo \F::app()->current_user?>';
	</script>
</head>
<body>
<div class="wrapper">
	<?php $this->renderPartial('layouts/_header')?>
	<?php $this->renderPartial('layouts/_mobile_header')?>
	<?php if($show_banner){
		F::widget()->load('banner');
	}?>
	<?php echo $content?>
	<?php $this->renderPartial('layouts/_footer');?>
	<div class="page-copyright">
		<span><?php echo Option::get('site:copyright')?></span>
	</div>
</div>
<a href="#" class="scroll-to-top" title="回到顶部"></a>
<script type="text/javascript" src="<?php echo $this->appStatic('js/common.js')?>"></script>
<script>
$(function(){
	common.init();
});
</script>
</body>
</html>