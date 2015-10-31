<?php
use fay\models\Option;
use fay\helpers\Html;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php if(!empty($canonical)){?>
<link rel="canonical" href="<?php echo $canonical?>" />
<?php }?>
<title><?php if(!empty($title)){
	echo $title, '_';
}
echo Option::get(site.sitename)?></title>
<meta content="<?php if(isset($keywords))echo Html::encode($keywords);?>" name="keywords" />
<meta content="<?php if(isset($description))echo Html::encode($description);?>" name="description" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->appStatic('css/gb.css')?>" >
<?php echo $this->getCss()?>
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo F::session()->get('user.id', 0)?>';
</script>
</head>
<body>
<div class="wrap">
<?php include '_header.php';?>
<?php echo $content?>
<?php include '_footer.php';?>
</div>
<script type="text/javascript" src="<?php echo $this->appStatic('js/gb.js')?>"></script>
</body>
</html>