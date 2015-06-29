<?php
use fay\models\Option;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo $this->assets('static/siwi/css/dialog.css" rel="stylesheet" type="text/css')?>" />
<?php echo $this->getCss()?>
<script src="<?php echo $this->assets('js/jquery-1.7.1.min.js" type="text/javascript')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo \F::app()->session->get('id', 0)?>';
</script>
<?php if(!empty($canonical)){?>
<link rel="canonical" href="<?php echo $canonical?>" />
<?php }?>
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
<![endif]-->
<title><?php if(!empty($subtitle))echo $subtitle . ' | '?><?php echo Option::get(site.sitename)?></title>
</head>
<body class="dialog">
<header class="subtitle">
	<a href="javascript:;" class="close-link"><img src="<?php echo $this->assets('static/sx54/images/close-cross.png')?>" /></a>
	<h1><?php if(isset($subtitle)) echo $subtitle?></h1>
</header>
<div class="main">
	<?php echo $content;?>
</div>
<script type="text/javascript" src="<?php echo $this->assets('static/sx54/js/common.js')?>"></script>
<script>
common.init();
//关闭按钮
$(".close-link").click(function(){
	parent.$.fancybox.close();
});
</script>
</body>
</html>