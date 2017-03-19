<?php
/**
 * @var $this \fay\core\View
 * @var $access_token string
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<?php if(!empty($canonical)){?>
		<link rel="canonical" href="<?php echo $canonical?>" />
	<?php }?>
	<title><?php if(!empty($title)){
			echo $title;
		}?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
	<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
	<script type="text/javascript" src="<?php echo $this->appAssets('js/common.js')?>"></script>
	<script>
		system.base_url = '<?php echo $this->url()?>';
		system.user_id = '<?php echo \F::app()->current_user?>';
	</script>
	<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" >
	<?php echo $this->getCss()?>
	<style>
		html,body,h1,ul,li,fieldset{padding:0;margin:0;border:0 none;font-size:14px;line-height:1.5}
		body{background-color:#fdfdfd;}
		a{text-decoration:none}
		
		.top-title{height:55px;line-height:55px;color:#fff;background-color:#E50112;text-align:center;font-size:18px}
		.content{padding:20px}
		.content p{line-height:1.5}
	</style>
</head>
<body>
<div class="wrapper">
	<h1 class="top-title"><?php echo \fay\helpers\HtmlHelper::encode($post['post']['title'])?></h1>
	<div class="content"><?php echo $post['post']['content']?></div>
</div>
</body>
</html>