<?php
use fay\models\Option;
use fay\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link type="image/x-icon" href="<?php echo $this->assets('favicon.ico')?>" rel="shortcut icon" />
<meta content="<?php if(isset($keywords))echo Html::encode($keywords);?>" name="keywords" />
<meta content="<?php if(isset($description))echo Html::encode($description);?>" name="description" />
<?php if(!empty($canonical)){?>
<link rel="canonical" href="<?php echo $canonical?>" />
<?php }?>
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
<![endif]-->
<link type="text/css" rel="stylesheet" href="<?php echo $this->appStatic('css/style.css')?>" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/font-awesome.min.css')?>" />
<?php echo $this->getCss()?>
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo \F::session()->get('user.id', 0)?>';
</script>
<title><?php if(!empty($title))echo $title . ' | '?><?php echo Option::get('site:sitename')?></title>
</head>
<body>
<?php $this->renderPartial('layouts/_header', array(
	'current_header_menu'=>$current_header_menu,
));?>
<?php echo $content?>
<?php $this->renderPartial('layouts/_footer');?>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/analyst.min.js')?>"></script>
<script>_fa.init();</script>

</body>
</html>