<?php
use fay\services\Option;
use fay\helpers\Html;
use fay\models\Flash;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->appStatic('css/style.css')?>" />
<?php echo $this->getCss()?>
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.7.1.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo \F::app()->current_user?>';
</script>
<link type="image/x-icon" href="<?php echo $this->assets('favicon.ico" rel="shortcut icon')?>" />
<!--[if IE 6]>
	<script type="text/javascript" src="<?php echo $this->assets('js/DD_belatedPNG_0.0.8a-min.js')?>"></script>
<![endif]-->
<meta content="<?php if(isset($keywords))echo Html::encode($keywords);?>" name="keywords" />
<meta content="<?php if(isset($description))echo Html::encode($description);?>" name="description" />
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
<![endif]-->
<title><?php if(!empty($title))echo $title . '_'?><?php echo Option::get('site:sitename')?></title>
<style>
body{background-position:center 112px;}
</style>
</head>
<body>
<div class="wrapper">
	<?php include '_header.php'?>
	<?php include '_navigation.php'?>
	<div class="w1000 clearfix bg-white mt10">
		<?php include '_left.php'?>
		<div class="ml240">
			<?php echo $content?>
		</div>
	</div>
	<?php include '_footer.php'?>
	<?php echo Flash::get();?>
</div>
<script type="text/javascript" src="<?php echo $this->appStatic('js/common.js')?>"></script>
<script>
$(function(){
	common.init();
})
</script>
</body>
</html>