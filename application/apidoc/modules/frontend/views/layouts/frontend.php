<?php 
use fay\models\Option;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />

<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/font-awesome.min.css')?>" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->appStatic('css/style.css')?>" >
<?php echo $this->getCss()?>

<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<?php if(!empty($canonical)){?>
<link rel="canonical" href="<?php echo $canonical?>" />
<?php }?>
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
<![endif]-->
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo F::session()->get('user.id', 0)?>';
</script>
<script type="text/javascript" src="<?php echo $this->appStatic('js/common.js')?>"></script>
<title><?php echo empty($title) ? '' : $title . ' | '?><?php echo Option::get('site:sitename')?></title>
</head>
<body id="faycms">
<div class="wrapper">
	<?php include '_sidebar_menu.php'?>
	<div class="container main-content">
		<div class="page-title">
			<div class="title-env">
				<h1 class="title"><?php
					echo isset($title) ? $title : '无标题';
					if($subtitle){
						echo ' <span>(', $subtitle, ')</span>';
					}
				?></h1>
			</div>
		</div>
		<?php echo $content?>
	</div>
</div>
<script>
$(function(){
	common.init();
});
</script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/analyst.min.js')?>"></script>
<script>_fa.init();</script>
</body>
</html>