<?php
use fay\models\Option;
use fay\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />
<meta content="<?php if(isset($keywords))echo $keywords;?>" name="keywords" />
<meta content="<?php if(isset($description))echo $description;?>" name="description" />
<?php if(!empty($canonical)){?>
<link rel="canonical" href="<?php echo $canonical?>" />
<?php }?>
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->url()?>js/html5.js"></script>
<![endif]-->
<link type="text/css" rel="stylesheet" href="<?php echo $this->url()?>static/siwi/css/style.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->url()?>static/siwi/css/user.css" />
<?php echo $this->getCss()?>
<script type="text/javascript" src="<?php echo $this->url()?>js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/system.min.js"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo \F::app()->session->get('id', 0)?>';
</script>
<title><?php if(!empty($title))echo $title . ' | '?><?php echo Option::get('site.sitename')?></title>
</head>
<body>
<?php include MODULE_PATH.'frontend/layouts/_header.php';?>
<div class="g-con">
	<div class="g-mn">
		<div class="shot-menu clearfix">
			<ul>
				<li>
					<?php echo Html::link('发布作品', array('user/work/create'), array(
						'class'=>(isset($current_directory) && $current_directory == 'work') ? 'crt' : '',
					))?>
				</li>
				<li>
					<?php echo Html::link('发布博文', array('user/post/create'), array(
						'class'=>(isset($current_directory) && $current_directory == 'blog') ? 'crt' : '',
					))?>
				</li>
				<li>
					<?php echo Html::link('上传素材', array('user/material/create'), array(
						'class'=>(isset($current_directory) && $current_directory == 'material') ? 'crt' : '',
					))?>
				</li>
				<li>
					<?php echo Html::link('收集网站', array('user/site/create'), array(
						'class'=>(isset($current_directory) && $current_directory == 'site') ? 'crt' : '',
					))?>
				</li>
				<li>
					<a href="javascript:;">上传灵感</a>
				</li>
			</ul>
		</div>
		<div class="g_mnc">
			<?php echo $content;?>
		</div>
	</div>
</div>
<?php include MODULE_PATH.'frontend/layouts/_footer.php';?>
</body>
</html>